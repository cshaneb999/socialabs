<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    class VideoConvertHook {

        public function start($params) {
            exec("pgrep ffmpeg", $pids);
            if (empty($pids)) {
                $query = "SELECT video.guid FROM video LEFT JOIN file ON video.video_guid=file.guid WHERE (video.processed != 'true' && video.processed != 'processing' && video.video_type = 'upload');";
                $results = Dbase::getResultsArray($query);
                foreach ($results as $result) {
                    $video_guid = $result['guid'];
                    $video = getEntity($video_guid, true);
                    if (is_a($video, "SociaLabs\\Video")) {
                        $file_guid = $video->video_guid;
                        if (!file_exists(SITEDATAPATH . "videos/$file_guid/video.mp4")) {
                            $file = getEntity($file_guid, true);
                            FileSystem::makePath(SITEDATAPATH . "videos/" . $file_guid, 0777);
                            $file_location = $file->file_location;
                            exec("pgrep ffmpeg", $pids);
                            if (empty($pids)) {
                                if (file_exists($file_location)) {
                                    $target_dir = SITEDATAPATH . "videos/" . $file->guid . "/";
                                    ini_set('max_execution_time', 3000);
                                    $ffmpeg = \FFMpeg\FFMpeg::create(array(
                                                'ffmpeg.binaries' => rtrim(shell_exec("which ffmpeg")),
                                                'ffprobe.binaries' => rtrim(shell_exec("which ffprobe")),
                                                'timeout' => 7200,
                                                'ffmpeg.threads' => 6
                                                    )
                                    );
                                    $oldmask = umask(0);
                                    $video->proccessed = "processing";
                                    $video->save();
                                    $video_file = $ffmpeg->open($file_location);
                                    $video_file
                                            ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(10))
                                            ->save($target_dir . 'frame.jpg');
                                    $video_file
                                            ->filters()
                                            ->resize(new \FFMpeg\Coordinate\Dimension(320, 240))
                                            ->synchronize();
                                    $video_file
                                            ->save(new \FFMpeg\Format\Video\X264('libfdk_aac', 'libx264'), $target_dir . 'video.mp4')
                                            ->save(new \FFMpeg\Format\Video\WebM(), $target_dir . 'video.webm')
                                            ->save(new \FFMpeg\Format\Video\Ogg(), $target_dir . 'video.ogv');
                                    $video = getEntity($video_guid);
                                    $video->processed = "true";
                                    $video->save();
                                    umask($oldmask);
                                    $user_guid = $video->owner_guid;
                                    $message = "Your video has been processed.";
                                    $link = $video->getURL();
                                    notifyUser($user_guid, $message, $link);
                                    continue;
                                } else {
                                    $video->processed = "true";
                                    $video->save();
                                }
                            }
                        } else {
                            $video->processed = "true";
                            $video->save();
                        }
                    }
                }
            }
        }

    }

}
<?php

/**
 * PHP version 5
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 SociaLabs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 *
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

class Video extends Entity {

    public $default_icon = "assets/img/avatars/default_video.png";
    public $processed = "false";

    public function __construct() {
        $this->type = "Video";
    }

    public function getURL() {
        return getSiteURL() . "videos/view/" . $this->guid;
    }

    public function icon($thumbnail = 560, $class = NULL, $img_tag = true, $style = NULL, $processed = true) {
        switch ($this->video_type) {
            default:
                $return = NULL;
                if ($thumbnail <= EXTRALARGE) {
                    $id = $this->getYoutubeID();
                    if (strpos($class, "img-responsive") === false) {
                        $return = "<span style='width:{$thumbnail}px' class='$class'>";
                    }
                    $return .= "<img src='http://img.youtube.com/vi/$id/0.jpg' style='max-width:{$thumbnail}px;' class='img-responsive $class' alt=''/>";
                    if (strpos($class, "img-responsive") === false) {
                        $return .= "</span>";
                    }
                    return $return;
                }
                return $this->embedHTML($thumbnail, $class);
                break;
            case "upload":
                $guid = $this->video_guid;
                if ($thumbnail < LARGE) {
                    $source = getSiteURL() . "core_plugins/videos/views/output/video_image_viewer.php?guid=$guid";
                    return "<img src='$source' alt='' class='img-responsive $class' style='max-width:{$thumbnail}px'/>";
                } else {
                    if ($this->processed == "true") {
                        $mp4 = getSiteURL() . "core_plugins/videos/views/output/video_viewer.php?guid=$guid&path=mp4";
                        $webm = getSiteURL() . "core_plugins/videos/views/output/video_viewer.php?guid=$guid&path=webm";
                        $ogg = getSiteURL() . "core_plugins/videos/views/output/video_viewer.php?guid=$guid&path=ovg";
                        $poster = getSiteURL() . "core_plugins/videos/views/output/video_image_viewer.php?guid=$guid";
                        return <<<HTML
                            <video poster="$poster" controls="controls" preload="none" width="{$thumbnail}px">
                                <source type="video/mp4" src="$mp4" />
                                <source type="video/webm" src="$webm" />
                                <source type="video/ogg" src="$ogg" />
                            </video>
HTML;
                    } else {
                        $source = getSiteURL() . "assets/img/avatars/processing_video.jpg";
                        return "<img src='$source' alt='' class='img-responsive $class' style='max-width:{$thumbnail}px'/>";
                    }
                }
                break;
        }
    }

    public function embedHTML($width = 560, $class = NULL) {
        $height = (9 * $width) / 16;
        $id = $this->getYoutubeID();
        if ($id) {
            return <<<HTML
        <iframe id="ytplayer" type="text/html" width="{$width}px" height="{$height}px" src="https://www.youtube.com/embed/$id?rel=0&showinfo=0&color=white&iv_load_policy=3" frameborder="0" class="$class" allowfullscreen></iframe> 
HTML;
        } else {
            $this->delete();
        }
    }

    public function getYoutubeID() {
        $id = false;
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $this->url, $matches);
        if (is_array($matches) && !empty($matches)) {
            $id = $matches[1];
        }
        return $id;
    }

    public function createAvatar($entity = false, $filename = false, $copy = false) {
        $target_dir = getDataPath() . "videos" . "/" . $this->video_guid . "/";
        $file_entity = getEntity($this->video_guid);
        makePath($target_dir, 0777);
        $ffmpeg = \FFMpeg\FFMpeg::create(array(
                    'ffmpeg.binaries' => rtrim(shell_exec("which ffmpeg")),
                    'ffprobe.binaries' => rtrim(shell_exec("which ffprobe")),
                    'timeout' => 7200,
                    'ffmpeg.threads' => 6
        ));
        $file_location = $file_entity->file_location;
        $video = $ffmpeg->open($file_location);
        $video
                ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(2))
                ->save($target_dir . 'frame.jpg');
    }

}

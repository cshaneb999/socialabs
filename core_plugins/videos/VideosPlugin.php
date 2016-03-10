<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class VideosPlugin {

    public function __construct() {
        new CSS("videos", getSitePath() . "core_plugins/videos/vendor/mediaelement/build/mediaelementplayer.css");
        new FooterJS("video", getSiteURL() . "core_plugins/videos/assets/js/video.js", 5001, true);
        new FooterJS("video_player", getSiteURL() . "core_plugins/videos/vendor/mediaelement/build/mediaelement-and-player.min.js", 5000);
        new StorageType("Video", "description", "text");
        new StorageType("Video", "url", "text");
        new StorageType("Videoalbum", "description", "text");
        new StorageType("Videoalbum", "icon_filename", "text");
        new ViewExtension("header:after", "page_elements/video_selector");
        new ViewExtension("page_elements/site_js", "videos/video_footer");
        new ViewExtension("profile/left", "videos/profile");
        new ViewExtension("tinymce/buttons", "videos/tinymce_button");
        new Hook("cron:minute", "VideoConvertHook");
        new Admintab("video_settings", 1000, array());
        new Setting("allow_video_uploads", "dropdown", array(
            "no" => "No",
            "yes" => "Yes"
                ), "video_settings");
        new Metatag("Video", "title", getSiteName() . " | Videos");
        new MenuItem(array(
            "name" => "videos",
            "href" => "videos",
            "label" => "<i class='icon ion-social-youtube'></i><p>Videos</p>",
            "menu" => "header_left"
        ));
    }

    /**
     * @param string $filename
     * @param Video $container
     */
    static function processUploadedVideo($filename, $container) {
        $file = new File;
        $file->access_id = $container->access_id;
        $file->owner_guid = getLoggedInUserGuid();
        $file->container_guid = $container->guid;
        $file_guid = $file->save();
        FileSystem::uploadFile($filename, $file_guid, array(
            "mov",
            "mpeg4",
            "mp4",
            "avi",
            "wmv",
            "mpegps",
            "flv",
            "3gpp",
            "webm",
            "3gp",
            "3g2",
            "m4v",
            "m2v",
            "mkv"
        ));
        return $file_guid;
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class PhotosPlugin {

    public function __construct() {
        new CSS("photo", getSitePath() . "core_plugins/photos/assets/css/photos.css");
        new StorageType("Photo", "description", "text");
        new StorageType("Photoalbum", "description", "text");
        new StorageType("Photoalbum", "icon_filename", "text");
        new FooterJS("photo", getSiteURL() . "core_plugins/photos/assets/js/photo.js", 5000, true);
        new ViewExtension("header:after", "page_elements/photo_selector");
        new ViewExtension("profile/left", "photos/profile");
        new ViewExtension('pages/home_stats', "pages/photo_stats");
        new ViewExtension("page_elements/site_js", "photos/photo_footer");
        new ViewExtension("tinymce/buttons", "photos/tinymce_button");
        if (isEnabledPlugin("groups")) {
            new ViewExtension("groups/right", "photos/group_photos");
        } else {
            removeViewExtension("groups/right", "photos/group_photos");
        }
        new Metatag("Photo", "title", getSiteName() . " | Photos");
        new MenuItem(array(
            "name" => "photos",
            "href" => "photos",
            "label" => "<i class='icon ion-images'></i><p>Photos</p>",
            "menu" => "header_left"
        ));
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$classes = Vars::get("classes");
$classes = str_replace("videopicker_album_gallery_item well well-sm ", "", $classes);
$classes = str_replace(" active", "", $classes);
$classes = str_replace("album_", "", $classes);
$videos = listEntities(array(
            "type" => "Video",
            "metadata_name" => "container_guid",
            "metadata_value" => intval($classes),
            "view_type" => "videopicker_gallery"
        ));
echo $videos;
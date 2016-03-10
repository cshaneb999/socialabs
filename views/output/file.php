<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;


$guid = Vars::get("guid");
$thumbnail = getInput("thumbnail");
$file = getEntity($guid);
$mime = $file->mime_type;
$title = (isset($file->title) ? $file->title : "");
$show_title = getInput("show_title");
switch ($mime) {
    case "image/jpeg":
    case "image/png":
    case "image/gif":
        $image_url = Image::getImageURL($guid, $thumbnail);
        echo "<img src='$image_url' title='$title' data-title='$title' data-toggle='tooltip' class='img-responsive' alt='$file->title'/>";
        if ($show_title == "true") {
            echo "<p class='small text-center'>$file->title</p>";
        }
        break;
    default:
        $image_url = getSiteURL(). "plugins/files/assets/img/file_avatar.png";
        echo "<img src='$image_url' title='$title' class='img-responsive' data-title='$title' data-toggle='tooltip' alt='$file->title'/>";
        if ($show_title == "true") {
            echo "<p class='small text-center'>$file->title</p>";
        }
        break;
}
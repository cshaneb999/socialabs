<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class VideosPageHandler extends PageHandler {

    public function __construct() {
        gateKeeper();
        $title = $body = $button = $wrapper_class = NULL;
        switch (pageArray(1)) {
            case "view":
                $guid = pageArray(2);
                $video = getEntity($guid);
                if ($video) {
                    if ($video->title) {
                        $title = $video->title;
                    } else {
                        $title = "&nbsp;";
                    }
                    if ((getLoggedInUserGuid() == $video->owner_guid) || adminLoggedIn()) {
                        $delete_url = addTokenToURL(getSiteURL() . "action/deleteVideo/$guid");
                        $button = "<a class='btn btn-info' href='" . getSiteURL() . "videos/edit/$guid'>Edit</a>";
                        $button .= "<a class='btn btn-danger confirm' href='$delete_url'>Delete</a>";
                    }
                    $body = display("pages/video");
                } else {
                    forward();
                }
                break;
            case "edit":
                $title = "Edit Video Details";
                $body = drawForm(array(
                    "name" => "edit_video",
                    "method" => "post",
                    "action" => "editVideo",
                ));
                break;
            case "add":
                $title = "Add a Video";
                $body = drawForm(array(
                    "name" => "add_video",
                    "method" => "post",
                    "action" => "AddVideo",
                    "enctype" => "multipart/form-data"
                ));
                break;
            default:
            case "albums":
                switch (pageArray(2)) {
                    default:
                        $guid = pageArray(1);
                        if (!$guid) {
                            $guid = getLoggedInUserGuid();
                        }
                        $user = getEntity($guid);
                        if ($guid == getLoggedInUserGuid()) {
                            $name = "My";
                        } else {
                            $name = $user->full_name . "'s";
                        }
                        $title = $name . " Video Albums";
                        $body = display("pages/video_albums");
                        $button = "<a class='btn btn-success' href='" . getSiteURL() . "videos/albums/add'>Create an Album</a>";
                        break;
                    case "add":
                        $title = "Add a Video Album";
                        $body = drawForm(array(
                            "name" => "add_video_album",
                            "method" => "post",
                            "action" => "addVideoalbum",
                            "class" => "add_video_album_form",
                            "enctype" => "multipart/form-data"
                        ));
                        break;
                    case "view":
                        $guid = pageArray(3);
                        $album = getEntity($guid);
                        $title = $album->title;
                        $body = display("pages/video_album");
                        $delete_url = getSiteURL() . "action/deleteVideoalbum/$guid";
                        $delete_url = addTokenToURL($delete_url);
                        if ((getLoggedInUserGuid() == $album->owner_guid) || (adminLoggedIn())) {
                            $button = "<a class='btn btn-info' href='" . getSiteURL() . "videos/albums/edit/$guid'>Edit Album</a>";
                            $button .= "<a class='btn btn-danger' href='$delete_url'>Delete Album</a>";
                        }
                        $button .= "<a class='btn btn-success' href='" . getSiteURL() . "videos/add/$guid'>Add Video</a>";
                        $wrapper_class = "masonry4col";
                        break;
                    case "edit":
                        $body = drawForm(array(
                            "name" => "edit_video_album",
                            "method" => "post",
                            "action" => "editVideoalbum",
                            "enctype" => "multipart/form-data"
                        ));
                        $title = "Edit Album";

                        break;
                }
        }
        $this->html = drawPage(array(
            "header" => $title,
            "body" => $body,
            "button" => $button,
            "wrapper_class" => $wrapper_class
        ));
    }

}

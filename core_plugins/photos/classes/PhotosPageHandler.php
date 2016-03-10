<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class PhotosPageHandler extends PageHandler {

    public function __construct() {
        $title = $html = $buttons = $wrapper_class = NULL;
        switch (pageArray(1)) {
            case "view":
                $guid = pageArray(2);
                $photo = getEntity($guid);
                if ($photo) {
                    if ($photo->title) {
                        $title = $photo->title;
                    } else {
                        $title = " ";
                    }
                    if ((getLoggedInUserGuid() == $photo->owner_guid) || adminLoggedIn()) {
                        $delete_url = addTokenToURL(getSiteURL() . "action/deletePhoto/$guid");
                        $buttons = "<a class='btn btn-info' href='" . getSiteURL() . "photos/edit/$guid'>Edit</a>";
                        $buttons .= "<a class='btn btn-danger confirm' href='$delete_url'>Delete</a>";
                    }
                    $html = display("pages/photo");
                }
                break;
            case "edit":
                $title = "Edit Photo Details";
                $html = drawForm(array(
                            "name" => "edit_photo",
                            "method" => "post",
                            "action" => "editPhoto",
                ));
                break;
            case "add":
                $title = "Add a Photo";
                $html = drawForm(array(
                            "name" => "add_photo",
                            "method" => "post",
                            "action" => "AddPhoto",
                            "enctype" => "multipart/form-data"
                ));
                break;
            default:
            case "albums":
                gateKeeper();
                switch (pageArray(2)) {
                    default:
                        $guid = pageArray(1);
                        if (!$guid) {
                            $guid = getLoggedInUserGuid();
                        }
                        $user = getEntity($guid);
                        $name = $user->full_name . "'s";
                        if ($guid == getLoggedInUserGuid()) {
                            $name = "My";
                        }
                        $title = $name . " Photo Albums";
                        $html = display("pages/photo_albums");
                        $buttons = "<a class='btn btn-success' href='" . getSiteURL() . "photos/albums/add'>Create an Album</a>";
                        $wrapper_class = "masonry4col";

                        break;
                    case "add":
                        $title = "Add a Photo Album";
                        $html = drawForm(array(
                                    "name" => "add_photo_album",
                                    "method" => "post",
                                    "action" => "addPhotoalbum",
                                    "class" => "add_album_form",
                                    "enctype" => "multipart/form-data"
                        ));
                        break;
                    case "view":
                        $guid = pageArray(3);
                        $album = getEntity($guid);
                        $delete_url = addTokenToURL(getSiteURL() . "action/deleteAlbum/$guid");
                        $title = $album->title;
                        $html = display("pages/photo_album");
                        if ((getLoggedInUserGuid() == $album->owner_guid) || (adminLoggedIn())) {
                            $buttons = "<a class='btn btn-info' href='" . getSiteURL() . "photos/albums/edit/$guid'>Edit Album</a>";
                            $buttons .= "<a class='btn btn-danger confirm' href='$delete_url'>Delete Album</a>";
                        }
                        if ((getLoggedInUserGuid() == $album->owner_guid)) {
                            $buttons .= "<a class='btn btn-success' href='" . getSiteURL() . "photos/add/$guid'>Add Photo</a>";
                        }
                        $wrapper_class = "masonry4col";
                        break;
                    case "edit":
                        $form = drawForm(array(
                                    "name" => "edit_photo_album",
                                    "method" => "post",
                                    "action" => "editAlbum",
                                    "enctype" => "multipart/form-data"
                        ));
                        $html = drawPage(array(
                                    "header" => "Edit Album",
                                    "body" => $form
                        ));
                        break;
                }
        }
        $this->html = drawPage(
            array(
                    "header"=>$title,
                    "body"=>$html,
                    "button"=>$buttons,
                    "wrapper_class"=>$wrapper_class
                    )                             
        );
    }

}

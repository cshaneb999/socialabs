<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class DeleteVideoActionHandler {

    public function __construct() {
        $guid = pageArray(2);
        $video = getEntity($guid);
        classGateKeeper($video, "Video");
        $album_guid = $video->container_guid;
        if (loggedInUserCanDelete($video)) {
            $video->delete();
            new SystemMessage("Your video has been deleted.");
        }
        forward("videos/albums/view/$album_guid");
    }

}

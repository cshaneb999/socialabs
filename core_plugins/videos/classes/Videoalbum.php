<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class Videoalbum extends Entity {
    
    public $default_icon = "assets/img/avatars/default_video.png";

    public function __construct() {
        $this->type = "Videoalbum";
    }

    public function getURL() {
        return getSiteURL(). "videos/albums/view/" . $this->guid;
    }

}

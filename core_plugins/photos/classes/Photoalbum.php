<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

class Photoalbum extends Entity {
    
    public $default_icon = "assets/img/avatars/default_photo.svg";

    public function __construct() {
        $this->type = "Photoalbum";
    }

    public function getURL() {
        return getSiteURL(). "photos/albums/view/" . $this->guid;
    }

}

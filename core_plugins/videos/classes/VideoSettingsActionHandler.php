<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    class VideoSettingsActionHandler {

        public function __construct() {
            Setting::set("allow_video_uploads", getInput("allow_video_uploads"));
            new SystemMessage("Your settings have been updated.");
            forward();
        }

    }

}
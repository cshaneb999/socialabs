<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Activity page handler
 */
class ActivityPageHandler extends PageHandler {

    /**
     * Creates html for activity page
     */
    public function __construct() {
    $this->html = display("pages/activity");
    }

}

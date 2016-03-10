<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class to clear site cache
 */
class ClearSiteCacheActionHandler {

    /**
     * Clears site cache
     */
    public function __construct() {
        adminGateKeeper();
        Cache::clear();
        Cache::clear();
        Cache::clear();
        new SystemMessage(translate("system:cache:cleaned:success:system:message"));
        forward();
    }

}

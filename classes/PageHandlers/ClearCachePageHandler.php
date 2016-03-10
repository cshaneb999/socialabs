<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class to handle clear cache page
 */
class ClearCachePageHandler {

    /**
     * Clears cache
     */
    public function view() {
        adminGateKeeper();
        Cache::clear();
        new SystemMessage("All caches have been cleared.");
        forward();
    }

}

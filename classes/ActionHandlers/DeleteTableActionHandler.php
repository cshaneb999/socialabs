<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class that deletes database table
 */
class DeleteTableActionHandler {

    /**
     * Deletes database table
     */
    public function __construct() {
        adminGateKeeper();
        $name = pageArray(2);
        if ($name != "user") {
            $query = "DELETE FROM `entities` WHERE `type` = '$name'";
            Dbase::query($query);
            $query = "DROP TABLE `$name`";
            Dbase::query($query);
            Systemvariable::set("setup_complete", "false");
            clearCache();
            Cache::clear();
            Cache::clear();
            Cache::clear();
            new SystemMessage("Your table has been deleted.");
        }
        forward();
    }

}

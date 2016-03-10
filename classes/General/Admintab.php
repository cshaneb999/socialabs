<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class for handling admin tabs
 */
class Admintab {

    /**
     * Creates an admin tab
     * 
     * @param string $name  Name of the admin tab
     * @param int $weight   Weight of the admin tab (larger numbers 
     * @param string $buttons Buttons to show on the admin panel
     */
    public function __construct($name = false, $weight = 500, $buttons = array()) {
        $exists = false;
        $admin_tabs = Cache::get("admin_tabs", "session");
        if (!$admin_tabs) {
            $admin_tabs = array();
        }
        foreach ($admin_tabs as $tab) {
            if ($tab->name == $name) {
                $exists = true;
            }
        }
        if (!$exists) {
            $admin_tab = new \stdClass();
            $admin_tab->name = $name;
            $admin_tab->weight = $weight;
            $admin_tab->buttons = $buttons;
            $admin_tabs[] = $admin_tab;
            new Cache("admin_tabs", $admin_tabs, "session");
        }
    }

    /**
     * Returns an admin tab or array of all admin tabs
     * 
     * @param string $name  if provided, returns admin tab corresponding to that name, otherwise returns array of all admin tabs
     * @return mixed  Either named admin tab, or array of all admin tabs.
     */
    static function get($name = false) {
        $admin_tabs = Cache::get("admin_tabs", "session");
        if ($name) {
            foreach ($admin_tabs as $tab) {
                if ($tab->name == $name) {
                    return $tab;
                }
            }
        } else {
            usort($admin_tabs, function ($a, $b) {
                return strcmp($a->name, $b->name);
            });
            return $admin_tabs;
        }
    }

    /**
     * Removes admin tabs
     * 
     * @global type $admin_tabs All admin tabs
     * @param type $name  Name of admin tab to remove
     */
    static function removeAdmintab($name) {
        $admin_tabs = Cache::get("admin_tabs", "session");
        foreach ($admin_tabs as $key => $tab) {
            if ($tab->name == $name) {
                unset($key);
                return;
            }
        }
    }

    /**
     * Deletes all admin tabs
     */
    static function deleteAll() {
        adminGateKeeper();
        new Cache("admin_tabs", NULL, "session");
    }

}

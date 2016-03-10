<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Classed used to save and retrieve system variables
 */
class Systemvariable extends Entity {

    /**
     * Creates system variable
     */
    public function __construct() {
        $this->type = "Systemvariable";
        $this->owner_guid = 0;
        $this->access_id = "system";
    }

    /**
     * Gets a system setting
     * 
     * @param string $name  Name of setting to get
     * @param string $default Value to return if setting not set
     * @return string Value of setting
     */
    static function get($name, $default = false) {
        $variable = Cache::get("system_variable_" . $name, "site");
        if ($variable) {
            return $variable;
        }
        $variable = getEntity(array(
            "type" => "Systemvariable",
            "metadata_name" => "name",
            "metadata_value" => $name
        ));
        if (!$variable) {
            return $default;
        }
        if ($variable->value) {
            new Cache("system_variable_" . $name, $variable->value, "site");
            return $variable->value;
        } else {
            return $default;
        }
    }

    /**
     * Saves a system variable
     * 
     * @param type $name  Name of variable to save
     * @param type $value Value of variable to save
     */
    static function set($name, $value) {
        $variable = Cache::get("system_variable_" . $name, "site");
        if ($variable == $value) {
            return true;
        }
        if ($name && $value) {
            $variable = getEntity(array(
                "type" => "Systemvariable",
                "metadata_name" => "name",
                "metadata_value" => $name
            ));
            if (!$variable) {
                $variable = new Systemvariable;
                $variable->name = $name;
                $variable->value = $value;
                $variable->save();
            }
            if ($variable->value != $value) {
                $variable->value = $value;
                $variable->save();
            }
        }
        new Cache("system_variable_" . $name, $value, "site");
        return;
    }

}

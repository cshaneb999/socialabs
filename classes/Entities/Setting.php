<?php

/**
 * PHP version 5
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 SociaLabs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 *
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

/**
 * Class to create system settings
 */
class Setting extends Entity {

    /**
     * Constructor creates the setting object, with no value
     * 
     * @param string $name  Name of setting
     * @param string $field_type    Field type
     * @param array $options    Array of options=>values if dropdown fieldtype is used
     * @return boolean  true
     */
    public function __construct($name = false, $field_type = "text", $options = array(), $tab = "general", $default = NULL) {
        if ($name) {
            $this->type = "Setting";
            $params = array(
                "metadata_name" => "name",
                "metadata_value" => $name
            );
            if (!$this->exists($params)) {
                $this->name = $name;
                $this->field_type = $field_type;
                $this->options = $options;
                $this->tab = $tab;
                $this->access_id = "system";
                $this->default = $default;
                $this->save();
            }
        }
    }

    /**
     * Used to set a system setting
     * 
     * @param string $name  Setting name
     * @param mixed $value  Value of setting
     * @return boolean  true
     */
    static function set($name, $value) {
        $setting = getEntity(array(
            "type" => "Setting",
            "metadata_name" => "name",
            "metadata_value" => $name
        ));
        if ($setting) {
            if ($setting->value != $value) {
                $setting->value = $value;
                $setting->save();
            }
        }
        return true;
    }

    /**
     * Gets the value of a system setting
     * 
     * @param string $name  Name of system setting
     * @return mixed|boolean    Returns setting value, or false if no value set
     */
    static function get($name) {
        $system_setting = getEntity(array(
            "type" => "Setting",
            "metadata_name" => "name",
            "metadata_value" => $name
        ));
        if ($system_setting) {
            return $system_setting->value;
        }
        return false;
    }

    /**
     * Returns all system settings for a particular tab
     * 
     * @return array    Array of system settings
     */
    static function getAll($tab = "general") {
        $return = array();
        $settings = getEntities(array(
            "type" => "Setting",
            "metadata_name" => "tab",
            "metadata_value" => $tab
        ));
        return $settings;
    }

    /**
     * Updates settings table
     */
    static function updateSettingsTable() {
        Init::loadDefaultSettings(true);
    }

}

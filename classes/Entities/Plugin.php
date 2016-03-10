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
 * Creates plugin object
 */
class Plugin extends Entity {

    public $plugin_order = 0;
    public $status = "disabled";
    public $name;
    public $requires;
    public $label;

    /**
     * Creates plugin object
     */
    public function __construct() {
        $this->type = "Plugin";
        $this->access_id = "system";
    }

    /**
     * Checks if a plugin is enabled
     * 
     * @return boolean true if enabled, false if not enabled
     */
    public function enabled() {
        if ($this->status == "enabled") {
            return true;
        }
        return false;
    }

    /**
     * Enables a plugin
     * 
     * @return boolean true
     */
    public function enable() {
        $requires = (isset($this->requires) ? $this->requires : array());
        if (!empty($requires) && is_array($requires)) {
            foreach ($requires as $required) {
                $required_plugin = getEntity(array(
                    "type" => "Plugin",
                    "metadata_name" => "name",
                    "metadata_value" => $required,
                    "ignore_access" => true
                ));
                if ($required_plugin) {
                    if ($required_plugin->status != "enabled") {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        $this->status = "enabled";
        $this->save();
        return true;
    }

    /**
     * Disables a plugin
     * 
     * @return boolean true
     */
    public function disable() {
        $this->status = "disabled";
        $this->save();
        return true;
    }

    /**
     * Gets an array of enabled plugins
     * 
     * @return array Array of enabled plugins
     */
    static function getEnabledPlugins($reversed = false) {
        if (class_exists("SociaLabs\\Cache")) {
            if ($reversed) {
                $reversed = "reversed";
            } else {
                $reversed = NULL;
            }
            $plugins = Cache::get("enabled_plugins_$reversed", "site");
            if ($plugins) {
                return $plugins;
            }
        }
        $params = array(
            "type" => "Plugin",
            "metadata_name" => "status",
            "metadata_value" => "enabled",
            "ignore_access" => true,
            "order_by" => "plugin_order",
            "order_reverse" => $reversed
        );

        $plugins = getEntities($params);
        if (class_exists("SociaLabs\\Cache")) {
            new Cache("enabled_plugins_$reversed", $plugins, "site");
        }
        return $plugins;
    }

    /**
     * Returns array of all system plugins
     * 
     * @return array    Array of system plugins
     */
    static function getAll() {
        $access = getIgnoreAccess();
        setIgnoreAccess();
        $plugins = getEntities(array(
            "type" => "Plugin"
        ));
        setIgnoreAccess($access);
        return $plugins;
    }

    /**
     * Checks if plugin is enabled
     * 
     * @param string $name  Name of plugin
     * @return mixed  Plugin object if enabled, false if not
     */
    static function isEnabledPlugin($name) {
        $plugin = getEntity(array(
            "type" => "Plugin",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "name",
                    "value" => $name
                ),
                array(
                    "name" => "status",
                    "value" => "enabled"
                )
            )
        ));
        return $plugin;
    }

    /**
     * Gets a list of plugins from the plugin folder, and adds them to the database
     * 
     * @return array Array of all plugins
     */
    static function getPluginsFromFileSystem() {
        $plugins_path = array();
        foreach (glob(SITEPATH . "plugins/*/") as $dir) {
            $plugins_path[] = $dir;
        }
        foreach (glob(SITEPATH . "core_plugins/*/") as $dir) {
            $plugins_path[] = $dir;
        }
        foreach ($plugins_path as $path) {
            if (file_exists($path . "manifest.json")) {
                $json = file_get_contents($path . "manifest.json");
                $manifest = json_decode($json, true);

                $plugin_name = $manifest['name'];
                $plugin_label = $manifest['label'];
                $plugin_requires = isset($manifest['requires']) ? $manifest['requires'] : array();

                // check if this plugin exists in database and update it if it does
                $existing_plugin = getEntity(array(
                    "type" => "Plugin",
                    "metadata_name" => "name",
                    "metadata_value" => $plugin_name
                ));
                if (!$existing_plugin) {
                    $existing_plugin = new Plugin;
                    $existing_plugin->name = $plugin_name;
                    $existing_plugin->requires = $plugin_requires;
                    $existing_plugin->label = $plugin_label;
                }
                $existing_plugin->save();
            }
        }
        new Cache("enabled_plugins_", false, "site");
        new Cache("enabled_plugins_reversed", false, "site");
    }

    /**
     * Returns a plugin object by name
     * 
     * @param string $name  Name of plugin
     * @return object|boolean   Plugin object or false if no object found
     */
    static function getPluginByName($name) {
        $plugin = getEntity(array(
            "type" => "Plugin",
            "metadata_name" => "name",
            "metadata_value" => $name,
            "limit" => 1
        ));
        return $plugin;
    }

}

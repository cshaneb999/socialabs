<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * A class to create view extensions
 * 
 * View extensions are used to extend site views.
 * 
 */
class ViewExtension {

    /**
     * Constructor creates view extension array and saves them to the session cache
     * 
     * @param string $target    Target view to be extended
     * @param string $source    Source view to extend
     * @param string $placement Before or after
     * @return boolean|null true
     */
    public function __construct($target = false, $source = false, $placement = "after") {
        $extension = array();
        if ($target && $source) {
            $view_extensions = self::getAll();
            if (isset($view_extensions[$target])) {
                $extension = $view_extensions[$target];
            } else {
                $extension = array();
            }
            foreach ($extension as $key => $value) {
                if (($value['src'] == $source) && ($value['placement'] == $placement)) {
                    return false;
                }
            }
            $new_extension = array(
                "src" => $source,
                "placement" => $placement
            );
            $extension[] = $new_extension;
            $view_extensions[$target] = $extension;
            new Cache("view_extensions", $view_extensions, "session");
            return true;
        }
    }

    /**
     * Returns view extensions 
     * 
     * @param string $path  path of view extension
     * @param string $position  Position of view extension
     * @return string   Parsed html of view extension
     */
    static function display($path, $position = "after") {
        $return = "";
        $plugin_view = false;
        $view_extensions = self::getAll();
        $plugins = Plugin::getEnabledPlugins(true);
        if (isset($view_extensions[$path])) {
            $extend_paths = $view_extensions[$path];
            foreach ($extend_paths as $extend_path) {
                if ($extend_path['placement'] == $position) {
// Check if view exists in plugins first
                    if ($plugins) {
                        foreach ($plugins as $plugin) {
                            $name = $plugin->name;
                            if (file_exists(SITEPATH . "plugins/$name/views/{$extend_path['src']}.php")) {
                                $return .= Page::getRenderedHTML(SITEPATH . "plugins/$name/views/{$extend_path['src']}.php");
                            }
                            if (file_exists(SITEPATH . "core_plugins/$name/views/{$extend_path['src']}.php")) {
                                $return .= Page::getRenderedHTML(SITEPATH . "core_plugins/$name/views/{$extend_path['src']}.php");
                            }
                        }
                    }
                    $file_path = SITEPATH . "views/" . $extend_path ['src'] . ".php";
                    if (file_exists($file_path)) {
                        $return .= Page::getRenderedHTML($file_path);
                    }
                }
            }
        }
        return $return;
    }

    /**
     * Returns all system view extensions
     * 
     * @return string
     */
    static function getAll() {
        return Cache::get("view_extensions", "session");
    }

    /**
     * Removes a view extension for the stack
     * 
     * @param string $target    View extension target
     * @param string $source    View extension source
     * @return boolean true if removed|false if not
     */
    static function remove($target, $source) {
        $view_extensions = self::getAll();
        if (isset($view_extensions[$target])) {
            $extension = $view_extensions[$target];
            foreach ($extension as $key => $value) {
                if ($value['src'] == $source) {
                    unset($extension[$key]);
                }
            }
            $view_extensions[$target] = $extension;
            new Cache("view_extensions", $view_extensions, "session");

            return true;
        }
        return false;
    }

}

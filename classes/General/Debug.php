<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    /**
     * Debug class used to display system information for debugging
     */
    class Debug {

        /**
         * Creates a debug message
         * @param string $message Debug message to create
         * @param string $name
         */
        public function __construct($name, $message) {
            self::writeDebugCache($name, $message);
        }

        /**
         * Clears all debug messages
         */
        static function clear() {
            unset($_SESSION[SITESECRET . "_debug"]);
        }

        /**
         * Writes to debug cache
         * 
         * @param string $name  Key of item to write
         * @param string $value  Value to write
         */
        static function writeDebugCache($name, $value) {
            if ($value == array(
                "type" => "Setting",
                "metadata_name" => "name",
                "metadata_value" => "debug_mode"
                    )) {
                return;
            }
            if (class_exists("SociaLabs\Setting")) {
                $debug_mode = Setting::get("debug_mode");
                if ($debug_mode !== "yes") {
                    return;
                }
            } else {
                return;
            }
            if (is_array($value) || is_object($value) || is_bool($value)) {
                $value = serialize($value);
            }
            $cache = readDebugCache();
            if (!$cache) {
                $cache = array();
            }
            $cache[$name][] = $value;
            $_SESSION[SITESECRET . "_debug"] = $cache;
            return;
        }

        /**
         * Reads debug cache
         * @return string Debug cache
         */
        static function readDebugCache() {
            $return = NULL;
            if (isset($_SESSION[SITESECRET . "_debug"])) {
                $return = $_SESSION[SITESECRET . "_debug"];
            }
            return $return;
        }

        /**
         * Returns html debug messages
         * 
         * @return string Debug messages
         */
        static function displayDebugMessages() {
            $cache = self::readDebugCache();
            if ($cache) {
                $return = "<div class='prettyprint'>";
                foreach ($cache as $key => $info) {
                    foreach ($info as $key2 => $in) {
                        if (@unserialize($in) !== false) {
                            $in = unserialize($in);
                        }
                        ob_start();
                        print_r($in);
                        $var = ob_get_contents();
                        ob_end_clean();
                        $return .= "<div class='container'><div class='well well-sm'><strong>$key</strong><br/><pre>" . $var . "</pre></div></div>";
                    }
                }

                $return .= "</div>";

                return $return;
            }
            return;
        }

    }

}
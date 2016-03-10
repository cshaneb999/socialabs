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

require_once(dirname(__FILE__) . "/dbase_settings.php");
require_once(dirname(__FILE__) . "/settings.php");
require_once(SITEPATH . "classes/General/Dbase.php");

if (!function_exists("SociaLabs\\pluginAutoloader")) {

    function pluginAutoloader($className) {
        $filename = str_replace("SociaLabs\\", "", $className);
        $filename = ucfirst($filename) . ".php";
        $query = "SELECT * FROM `plugin` WHERE `status` = 'enabled' ORDER BY `plugin_order`";
        $plugins = Dbase::getResultsArray($query);
        if (is_array($plugins) && !empty($plugins)) {
            foreach ($plugins as $plugin) {
                $file = $plugin['name'];
                if (file_exists(SITEPATH . "plugins/$file/classes/$filename")) {
                    require_once(SITEPATH . "plugins/$file/classes/$filename");
                    return true;
                };
                if (file_exists(SITEPATH . "plugins/$file/$filename")) {
                    require_once(SITEPATH . "plugins/$file/$filename");
                    return true;
                }
                if (file_exists(SITEPATH . "core_plugins/$file/classes/$filename")) {
                    require_once(SITEPATH . "core_plugins/$file/classes/$filename");
                    return true;
                }
                if (file_exists(SITEPATH . "core_plugins/$file/$filename")) {
                    require_once(SITEPATH . "core_plugins/$file/$filename");
                    return true;
                }
            }
        }
        $files = glob(SITEPATH . "classes/*/*.php");
        foreach ($files as $file) {
            $filename = str_replace("SociaLabs\\", "", $className);
            $filename = ucfirst($filename) . ".php";
            $basename = basename($file);
            if ($basename == $filename) {
                require_once($file);
            }
        }
    }

}

spl_autoload_register("SociaLabs\\pluginAutoloader");


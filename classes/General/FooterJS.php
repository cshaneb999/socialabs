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
 * Creates a footerjs element and adds it to the stack
 */
class FooterJS {

    /**
     * Creates the footerjs element
     * 
     * @param string $name    Name of javascript
     * @param string $js    Link to the javascript file
     * @param int $weight   Weight of the javascript
     */
    public function __construct($name = false, $js = false, $weight = 500, $init = false) {
        if ($name && $js) {
            $jsarray = Cache::get("footer_jsarray", "session");
            if (!isset($jsarray[$name])) {
                $jsarray[$name] = array(
                    "link" => $js,
                    "weight" => $weight,
                    "init" => $init
                );
                new Cache("footer_jsarray", $jsarray, "session");
            }
        }
    }

    /**
     * Removes footer javascript from the stack
     * 
     * @param string $name  Name of js to remove
     * @return boolean  true if removed, false if not
     */
    static function removeFooterJS($name) {
        $footerjs = Cache::get("footer_jsarray", "session");
        if (isset($footerjs[$name])) {
            unset($footerjs[$name]);
            new Cache("footer_jsarray", $footerjs, "session");
            return true;
        }
        return false;
    }

    /**
     * Returns formulated JS ready for HTML
     * 
     * @return string JS ready for HTML
     */
    static function draw() {
        $footerJSArray = Cache::get("footer_jsarray", "session");
        if (is_array($footerJSArray)) {
            $return = "\n\r";
            uasort($footerJSArray, function($a, $b) {
                return $a['weight'] - $b['weight'];
            });
            foreach ($footerJSArray as $footerJS) {
                $link = $footerJS['link'];
                $return .= "<script src='$link'></script>";
            }
            return $return;
        }
        return NULL;
    }

    /**
     * Returns array of footer js names
     * 
     * @return array  Array of footer js names
     */
    static function getFooterJSNameArray() {
        $return = array();
        $footerJSArray = Cache::get("footer_jsarray", "session");
        if (is_array($footerJSArray)) {
            uasort($footerJSArray, function($a, $b) {
                return $a['weight'] - $b['weight'];
            });
            foreach ($footerJSArray as $name => $footerJS) {
                if ($footerJS['init']) {
                    $return[] = $name;
                }
            }
        }
        return $return;
    }

}

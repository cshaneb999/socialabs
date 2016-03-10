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
 * Class for creating css and adding it to the stack
 */
class CSS {

    /**
     * Adds css to the stack
     * 
     * @param string $name Arbitrary name of the css (used primarily for removeCSS)
     * @param string $css Link to CSS
     * @param int $weight Weight of CSS (bigger numbers sink to the bottom of the stack)
     */
    public function __construct($name = false, $css = false, $weight = 500, $combine = false) {
        $cssArray = Cache::get("css_array", "session");
        if (!$cssArray) {
            $cssArray = array();
        }
        $cssArray[$name] = array(
            "css" => $css,
            "weight" => $weight,
            "combine" => $combine
        );
        new Cache("css_array", $cssArray, "session");
    }

    /**
     * Removes css from the stack
     * 
     * @param string $name Name of CSS to remove from the stack
     * @return boolean true
     */
    static function remove($name) {
        $cssArray = Cache::get("css_array", "session");
        if (isset($cssArray[$name])) {
            unset($cssArray[$name]);
        }
        new Cache("css_array", $cssArray, "session");
    }

    /**
     * Draws site css
     * 
     * @return string HTML site css
     */
    static function draw($scope = "internal") {
        $cssArray = Cache::get("css_array", "session");
        switch ($scope) {
            case "internal":
                return "<link href='" . getSiteURL() . "views/page_elements/css.php?page=" . currentPage() . "' rel='stylesheet' media='all'>";
                break;
            case "external":
                $return = NULL;
                if (is_array($cssArray)) {
                    foreach ($cssArray as $css) {
                        if (strpos($css['css'], "http") !== false) {
                            $return .= "<link href='" . $css['css'] . "' rel='stylesheet' media='all'>";
                        }
                    }
                }
                return $return;
                break;
        }
        return NULL;
    }

    static function getAll() {
        $cssArray = Cache::get("css_array", "session");
        return $cssArray;
    }

}

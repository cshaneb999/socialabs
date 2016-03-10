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
 * Class for creating and getting activity tabs
 */
class ActivityTab {

    /**
     * Creates an activity tab
     * 
     * @param string $name  Name of the activity tab
     * @param integer $weight  Weight of the activity tab. Higher numbers loaded later.
     * @param string $buttons Buttons to display when tab is active
     */
    public function __construct($name, $weight = 500, $buttons = array()) {
        if (currentPage() == "activity") {
            $activity_tabs = Cache::get("activity_tabs", "site");
            $activity_tabs[$name] = array(
                "name" => $name,
                "weight" => $weight,
                "button" => $buttons
            );
            new Cache("activity_tabs", $activity_tabs, "site");
        }
    }

    /**
     * Returns admin tab if $name provided, otherwise returns array of all admin tabs
     * 
     * @param string $name  Name of tab to return (if not provided, returns array of all admin tabs)
     * @return mixed  Admin tab by name if provided, array of admin tabs if not.
     */
    static function get($name = false) {
        $activity_tabs = Cache::get("activity_tabs", "site");
        if ($activity_tabs) {
            if ($name) {
                if (isset($activity_tabs[$name])) {
                    return $activity_tabs[$name];
                }
            }
            uasort($activity_tabs, function($a, $b) {

                if ($a == $b) {
                    return ($a['name'] < $b['name']) ? -1 : 1;
                }
                return ( $a['weight'] < $b['weight']) ? -1 : 1;
            });
            return $activity_tabs;
        } else {
            return array();
        }
    }

    /**
     * Removes admin tab from the stack
     * 
     * @param string $name  Name of admin tab to remove
     */
    static function remove($name) {
        $activity_tabs = Cache::get("activity_tabs", "site");
        if (isset($activity_tabs[$name])) {
            unset($activity_tabs[$name]);
        }
        new Cache("activity_tabs", $activity_tabs, "site");
    }

}

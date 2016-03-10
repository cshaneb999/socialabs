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
 * Class used to create a hook object
 */
class Hook {

    /**
     * Constructs a new hook
     * 
     * @param string $hook_name Name of the hook
     * @param string $class Name of the class to instantiate when the hook is called
     */
    public function __construct($hook_name = false, $class = false) {
        $hooks = Cache::get("hooks", "session");
        if (!isset($hooks[$hook_name])) {
            $hooks[$hook_name] = array();
        }
        if (!in_array($class, $hooks[$hook_name])) {
            $hooks[$hook_name][] = $class;
            new Cache("hooks", $hooks, "session");
        }
    }

    /**
     * Runs hooks set by plugins
     * 
     * @global array $hooks
     * @param string $hook_name Name of the hook to call
     * @param type $params parameters to send to the called function
     * @return mixed Data returned from the called function
     */
    static function run($hook_name, $params = NULL) {
        $return = NULL;
        if (isset($params['return'])) {
            $return = $params['return'];
        }
        $hooks = Cache::get("hooks", "site");
        if (isset($hooks[$hook_name])) {
            $hook_array = $hooks[$hook_name];
            foreach ($hook_array as $hook) {
                $class = "SociaLabs\\" . $hook;
                $return .= (new $class)->start($params);
            }
        }

        return $return;
    }

    /**
     * Returns all system hooks
     * 
     * @global array $hooks
     * @return array All system hooks
     */
    static function getAllHooks() {
        return Cache::get("hooks", "site");
    }

    static function remove($hook_name, $class) {
        $hooks = Cache::get("hooks", "session");
        if (isset($hooks[$hook_name])) {
            foreach ($hooks[$hook_name] as $key => $hook) {
                if ($hook == $class) {
                    unset($hooks[$hook_name][$key]);
                    if (empty($hooks[$hook_name])) {
                        unset($hooks[$hook_name]);
                    }
                }
            }
        }
        new Cache("hooks", $hooks, "session");
    }

}

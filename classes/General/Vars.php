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
 * Class used to store and retrieve variables
 */
class Vars {

    /**
     * Creates a new vars variable
     * 
     * @param string $name  Name of variable
     * @param mixed $value  Value of variable
     */
    public function __construct($name = false, $value = false) {
        $vars = Cache::get("vars", "session");
        if (!$vars) {
            $vars = array();
        }
        $vars[$name] = $value;
        new Cache("vars", $vars, "session");
    }

    /**
     * Gets the value of a vars variable by name
     * 
     * @param string $name  Name of variable
     * @param string $default   Value to return if variable isn't set
     * @return mixed  Value of variable
     */
    static function get($name = false, $default = false) {
        $vars = Cache::get("vars", "session");
        if (isset($vars[$name])) {
            return $vars[$name];
        } else {
            return $default;
        }
        return $vars;
    }

    /**
     * Clears all vars variables
     * 
     * @return boolean true
     */
    static function clear() {
        new Cache("vars", null, "session");
        return true;
    }

}

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
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

class ProfileField extends Entity {

    public function __construct($name = false, $label = false, $field_type = false, $options = array(), $required = "false", $class = null, $profile_type = "default", $weight = 500) {
        if ($name) {
            $profile_fields = Cache::get("profile_fields", "session");
            $profile_fields[$profile_type][$name] = array(
                "label" => $label,
                "field_type" => $field_type,
                "options" => $options,
                "required" => $required,
                "class" => $class,
                "weight" => $weight
            );
            new Cache("profile_fields", $profile_fields, "session");
            return true;
        }
        return false;
    }

    static function get($profile_type = "default") {
        $profile_fields = Cache::get("profile_fields", "session");
        $profile_fields = $profile_fields[$profile_type];
        $return = array();
        uasort($profile_fields, function($a, $b) {
            return $a['weight'] > $b['weight'];
        });
        foreach ($profile_fields as $name => $params) {
            $return[$name] = $params;
        }
        return $return;
    }

    static function remove($name, $profile_type = "default") {
        $profile_fields = Cache::get("profile_fields", "session");
        if (isset($profile_fields[$profile_type][$name])) {
            unset($profile_fields[$profile_type][$name]);
        }
        new Cache("profile_fields", $profile_fields, "session");
    }

}

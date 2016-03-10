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
 * Class used to save user settings.
 */
class Usersetting {

    /**
     * Creates a new user setting.
     * 
     * @param array $params name:  Name of usersetting to create
     *                      field_type:  Type of field to use (ie, dropdown, text)
     *                      options:  Array of options=>values used for dropdown field type
     *                      default_value:  Default value 
     */
    public function __construct($params) {
        $user_settings = Cache::get("user_settings", "session");
        if (!$user_settings) {
            $user_settings = array();
        }
        if (!isset($user_settings[$params['name']])) {
            $user_settings[$params['name']] = array(
                "field_type" => $params['field_type'],
                "options" => $params['options'],
                "default_value" => $params['default_value'],
                "tab" => $params['tab']
            );
            new Cache("user_settings", $user_settings, "session");
        }
    }

    /**
     * Lists user setting tabs
     * 
     * @return array  Array of user tabs.
     */
    static function listTabs() {
        $tabs = array();
        $user_settings = Cache::get("user_settings", "session");
        if (!$user_settings) {
            $user_settings = array();
        }
        foreach ($user_settings as $key => $value) {
            $tabs[] = $value['tab'];
        }
        return array_unique($tabs);
    }

}

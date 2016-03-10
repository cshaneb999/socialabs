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
 * A class used to create Translations.
 */
class Translation {

    /**
     * Constructor creates translation array and stores it in the session cache.
     * 
     * @param string $language  Language code, ie "en"
     * @param array $new_language_array Array of language elements
     */
    public function __construct($language = SITELANGUAGE, $new_language_array) {
        $language_array = Cache::get("translation_" . $language, "session");
        if (!is_array($language_array)) {
            $language_array = array();
        }
        $language_array = array_merge($language_array, $new_language_array);
        new Cache("translation_" . $language, $language_array, "session");
    }

    static function writeToFileCache() {
        $test = Cache::get("translation_" . SITELANGUAGE, "site");
        if (!$test) {
            $language_array = Cache::get("translation_" . SITELANGUAGE, "session");
            new Cache("translation_" . SITELANGUAGE, $language_array, "site");
        }
    }

    /**
     * Returns translation of string
     * 
     * @param string $string    String to be translated
     * @param array $args   Arguments to pass to string
     * @return string   Translated string
     */
    static function translate($string, $args = array()) {
        $language = SITELANGUAGE;
        $language_array = Cache::get("translation_" . $language, "site");
        if (!$language_array || !is_array($language_array)) {
            $language_array = array();
        }
        $setting = Setting::get("show_translations");
        if (!$setting) {
            $setting = "no";
        }
        if ($setting == "no") {
            if (isset($language_array[$string])) {
                $return = $language_array[$string];
            } else {
                $return = $string;
            }
            if (empty($args)) {
                return $return;
            } else {
                $return = vsprintf($return, $args);
                return $return;
            }
        } else {
            return $string;
        }
    }

}

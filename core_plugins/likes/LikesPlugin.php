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

class LikesPlugin {

    public function __construct() {
        new CSS("likes", getSitePath() . "core_plugins/likes/assets/css/style.css", 500);
        new FooterJS("likes", getSiteURL() . "core_plugins/likes/assets/js/likes.js", 5000);
    }

    static function countLikes($guid) {
        $count = getEntities(array(
            "type" => "Like",
            "count" => "true",
            "metadata_name" => "container_guid",
            "metadata_value" => $guid
        ));
        return $count;
    }

    static function loggedInUserHasLiked($guid) {
        $likes = getEntities(array(
            "type" => "Like",
            "limit" => 1,
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "owner_guid",
                    "value" => getLoggedInUserGuid()
                ),
                array(
                    "name" => "container_guid",
                    "value" => $guid
                )
            )
        ));
        if ($likes) {
            return true;
        }
        return false;
    }

    static function likers($guid) {
        $users = NULL;
        $likes = getEntities(array(
            "type" => "Like",
            "metadata_name" => "container_guid",
            "metadata_value" => $guid,
            "limit" => 100
        ));
        if ($likes) {
            foreach ($likes as $like) {
                $users .= getEntity($like->owner_guid)->full_name . "&#013;";
            }
            return $users;
        }
        return false;
    }

}

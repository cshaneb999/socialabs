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

class CreateChatActionHandler {

    function __construct() {
        // Check if chat already exists
        $user_one = getLoggedInUserGuid();
        $user_two = pageArray(2);
        $chat = getEntity(array(
            "type" => "Chat",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "user_one",
                    "value" => $user_one
                ),
                array(
                    "name" => "user_two",
                    "value" => $user_two
                )
            )
        ));
        if (!$chat) {
            $chat = getEntity(array(
                "type" => "Chat",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "user_one",
                        "value" => $user_two
                    ),
                    array(
                        "name" => "user_two",
                        "value" => $user_one
                    )
                )
            ));
        }
        if (!$chat) {
            $chat = new Chat;
            $chat->user_one = $user_one;
            $chat->user_two = $user_two;
            $chat->save();
        }
        $chat->user_one_closed = false;
        $chat->user_two_closed = false;
        $chat->save();
        forward();
    }

}

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

class MessagesPlugin {

    function __construct() {
        if (loggedIn()) {
            $count = self::countUnread();
            new MenuItem(array(
                "name" => "messages",
                "page" => "messages",
                "label" => "Messages <span class='badge'>$count</span> ",
                "menu" => "my_account",
                "weight" => 100
            ));
        }
        new CSS("messages", getSitePath() . "core_plugins/messages/assets/css/style.css", 400000);
        new FooterJS("messages", getSiteURL() . "core_plugins/messages/assets/js/messages.js", 5000, true);
        new StorageType("MessageElement", "subject", "text");
        new StorageType("MessageElement", "message", "text");
        new StorageType("Message", "subject", "text");
        new Usersetting(array(
            "name" => "notify_when_message",
            "field_type" => "dropdown",
            "options" => array(
                "email" => "Email",
                "site" => "Site",
                "both" => "Both",
                "none" => "None"
            ),
            "tab" => "notifications",
            "default_value" => "both"
        ));
    }

    static function countUnread() {
        $messages = getEntities(array(
            "type" => "Messageelement",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "to",
                    "value" => getLoggedInUserGuid()
                ),
                array(
                    "name" => "read",
                    "value" => "false"
                )
            ),
            "count" => true
        ));
        return $messages;
    }

}

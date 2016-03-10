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

class ChatPlugin {

    function __construct() {
        if (loggedIn()) {
            if (currentPage() == "profile") {
                if (isEnabledPlugin("Friends")) {
                    $user_one = pageArray(1);
                    $user_two = getLoggedInUserGuid();
                    if (FriendsPlugin::friends($user_one, $user_two)) {
                        new MenuItem(array(
                            "name" => "chat",
                            "menu" => "profile",
                            "label" => "Chat",
                            "page" => "action/CreateChat/" . $user_one,
                            "link_class" => "list-group-item list-group-item-success"
                        ));
                    }
                }
            }
        }
        new ViewExtension("page_elements/foot", "chat/chat_boxes");
        new CSS("chat", getSitePath() . "core_plugins/chat/assets/css/chat.css", 400);
        new FooterJS("chat", getSiteURL() . "core_plugins/chat/assets/js/chat.js", 400, true);
        new Usersetting(array(
            "name" => "notify_offline_chat",
            "field_type" => "dropdown",
            "options" => array(
                "yes" => "Yes",
                "no" => "No"
            ),
            "default_value" => "yes",
            "tab" => "notifications"
        ));
    }

}

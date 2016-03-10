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

class AddChatMessageActionHandler {

    function __construct($data) {
        $guid = $data['guid'];
        $text = $data['text'];
        $chat_message = new Chatmessage;
        $chat_message->text = $text;
        $chat_message->owner_guid = getLoggedInUserGuid();
        $chat_message->container_guid = $guid;
        $chat_message->save();
        // If recipient is offline, send them an email
        $chat = getEntity($guid);
        if (getLoggedInUserGuid() == $chat->user_one) {
            $recipient_guid = $chat->user_two;
        } else {
            $recipient_guid = $chat->user_one;
        }
        $recipient = getEntity($recipient_guid);
        if ($recipient->online == "false") {
            $offline_chats = $recipient->offline_chats;
            if (!is_array($offline_chats)) {
                $offline_chats = array(getLoggedInUserGuid());
                $recipient->offline_chats = $offline_chats;
                $recipient->save();
            }
            if (!in_array(getLoggedInUserGuid(), $recipient->offline_chats)) {
                $recipient->offline_chats[] = getLoggedInUserGuid();
                $recipient->save();
            }
            $setting = $recipient->notify_offline_chat;
            if ($setting == "yes") {
                new Email(array(
                    "to" => array(
                        "name" => "",
                        "email" => ""
                    ),
                    "from" => array(
                        "name" => "",
                        "email" => ""
                    ),
                    "subject" => translate("offline_message_email_subject"),
                    "body" => translate("offline_message_email_body"),
                    "html" => true
                ));
            }
        }
    }

}

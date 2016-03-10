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

class MessageReplyActionHandler {

    function __construct() {
        $guid = getInput("guid");
        $reply = getInput("reply");
        if (!$reply) {
            new SystemMessage("Message body cannot be left empty.");
            forward();
        }
        $message = getEntity($guid);
        $to = getLoggedInUserGuid() == $message->to ? $message->from : $message->to;
        $from = getLoggedInUserGuid();
        $to_user = getEntity($to);
        $from_user = getEntity($from);
        $message_element = new Messageelement;
        $message_element->message = $reply;
        $message_element->to = $to;
        $message_element->from = $from;
        $message_element->container_guid = $guid;
        $message_element->save();
        $link = getSiteURL() . "messages";
        notifyUser($to, "You have a new message from " . getLoggedInUser()->full_name, $link);
        sendEmail(array(
            "to" => array(
                "name" => $to_user->full_name,
                "email" => $to_user->email
            ),
            "from" => array(
                "name" => getSiteName(),
                "email" => getSiteEmail()
            ),
            "subject" => "You have a new message from " . getLoggedInUser()->full_name,
            "body" => "You have received a new message from " . getLoggedInUser()->full_name . "<br/><a href='$link'>Click here to view it.</a>",
            "html" => true
        ));
        new SystemMessage("Your message has been sent.");
        forward("messages/" . $message->guid);
    }

}

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

class AddCommentActionHandler {

    public function __construct($data = NULL) {
        gateKeeper();
        $logged_in_user = getLoggedInUser();

        if (!$data) {
            // Get the comment body
            $comment_body = getInput("comment");
            // Get container url
            $container_guid = getInput("guid");
        } else {
            $comment_body = $data['comment_body'];
            $container_guid = $data['container_guid'];
        }

        $container = getEntity($container_guid);
        $container_owner_guid = $container->owner_guid;
        if ($container_owner_guid) {
            $container_owner = getEntity($container_owner_guid);
        }
        $url = $container->getURL();
        if (!$url) {
            $url = getSiteURL();
        }

        // Create the comment
        CommentsPlugin::createComment($container_guid, $comment_body);
        if ($container_owner_guid) {
            if ($container_owner_guid != getLoggedInUserGuid()) {
                $params = array(
                    "to" => array(
                        $container_owner->full_name,
                        $container_owner->email
                    ),
                    "from" => array(
                        getSiteName(),
                        getSiteEmail()
                    ),
                    "subject" => "You have a new comment.",
                    "body" => "You have a new comment.  Click <a href='$url'>Here</a> to view it.",
                    "html" => true
                );
                switch ($logged_in_user->getSetting("notify_when_comment")) {
                    case "email":
                        sendEmail($params);
                        break;
                    case "none":
                        break;
                    case "site":
                        notifyUser($container_owner_guid, "You have a new comment.", $url);
                        break;
                    case "both":
                        sendEmail($params);
                        notifyUser($container_owner_guid, "You have a new comment.", $url);
                        break;
                }
            }
        }
        runHook("add:comment:after");

        if ((getLoggedInUserGuid() != $container_owner_guid) && $container_owner_guid) {
            new Activity(getLoggedInUserGuid(), "activity:comment", array(
                getLoggedInUser()->getURL(),
                getLoggedInUser()->full_name,
                $container_owner->getURL(),
                $container_owner->full_name,
                $container->getURL(),
                translate($container->type),
                truncate($comment_body)
            ));
        } elseif (!$container_owner_guid) {
            new Activity(getLoggedInUserGuid(), "activity:comment:own", array(
                getLoggedInUser()->getURL(),
                getLoggedInUser()->full_name,
                $container->getURL(),
                $container->title,
                translate($container->type),
                truncate($comment_body)
            ));
        }
        // Return to container page.
        forward();
    }

}

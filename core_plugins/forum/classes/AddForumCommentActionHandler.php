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

class AddForumCommentActionHandler {

    public function __construct() {
        gateKeeper();
        $email_users = array();
        $container_guid = getInput("container_guid");
        $topic = getEntity($container_guid);
        $category_guid = $topic->container_guid;
        $category = getEntity($category_guid);
        $description = getInput("comment");
        $comment = new Forumcomment;
        $comment->description = $description;
        $comment->container_guid = $container_guid;
        $comment->category_guid = $category_guid;
        $comment->owner_guid = getLoggedInUserGuid();
        $comment->save();
        new SystemMessage("Your comment has been posted.");
        new Activity(getLoggedInUserGuid(), "forum:comment:posted", array(
            getLoggedInUser()->getURL(),
            getLoggedInUser()->full_name,
            $topic->getURL(),
            $topic->title,
            truncate($comment->description)
                ), $container_guid, $category->access_id);
        $all_comments = getEntities(array(
            "type" => "Forumcomment",
            "metadata_name" => "container_guid",
            "metadata_value" => $container_guid
        ));
        $notify_users = array($topic->owner_guid);
        $container_owner_guid = $topic->owner_guid;
        $container_owner = getEntity($container_owner_guid);
        if (($container_owner->notify_when_forum_comment_topic_i_own == "email") || ($container_owner->notify_when_forum_comment_topic_i_own == "both")) {
            $email_users[] = $container_guid;
        }
        foreach ($all_comments as $comment) {
            $user_guid = $comment->owner_guid;
            $user = getEntity($user_guid);
            switch ($user->notify_when_forum_comment_topic_i_own) {
                case "both":
                    $notify_users[] = $comment->owner_guid;
                    $email_users[] = $comment->owner_guid;
                    break;
                case "email":
                    $email_users[] = $comment->owner_guid;
                    break;
                case "site":
                    $notify_users[] = $comment->owner_guid;
                    break;
                case "none":
                    break;
            }
        }
        $notify_users = array_unique($notify_users);
        foreach ($notify_users as $user_guid) {
            notifyUser($user_guid, translate("forum:comment:notification", array(
                getLoggedInUser()->getURL(),
                getLoggedInUser()->full_name,
                $topic->getURL(),
                $topic->title,
                truncate($description)
                    )), $topic->getURL());
        }

        foreach ($email_users as $user) {
            $params = array(
                "to" => array(
                    $user->full_name,
                    $user->email
                ),
                "from" => array(
                    getSiteName(),
                    getSiteEmail()
                ),
                "subject" => "You have a new comment.",
                "body" => "You have a new comment.  Click <a href='$url'>Here</a> to view it.",
                "html" => true
            );
            sendEmail($params);
        }
        forward();
    }

}

//<a href=''></a> also commented on <a href=''></a>
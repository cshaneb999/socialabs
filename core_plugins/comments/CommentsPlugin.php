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

class CommentsPlugin {

    public function __construct() {
        new CSS("comments", getSitePath() . "core_plugins/comments/assets/css/comments.css");
        new FooterJS("comments", getSiteURL() . "core_plugins/comments/assets/js/comments.js", 5000, true);
        new ViewExtension('pages/home_stats', "pages/comment_stats");

        new Usersetting(array(
            "name" => "notify_when_comment",
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

    static function createComment($container_guid, $comment_body) {
        $owner_guid = getLoggedInUserGuid();
        $comment = new Comment;
        $comment->container_guid = $container_guid;
        $comment->body = $comment_body;
        $comment->owner_guid = $owner_guid;
        $comment->save();
    }

}

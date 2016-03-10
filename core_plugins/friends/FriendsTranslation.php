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

class FriendsTranslation extends Translation {

    private $english = array(
        "add:friend:success" => "You have successfully added %s as a friend.",
        "remove:friend:success" => "You have successfully removed %s from your friends.",
        "friend:made" => "%s has made you a friend.",
        "activity_panel:friends" => "Friends",
        "activity_feed:friend:add" => "<a href='%s'>%s</a> and <a href='%s'>%s</a> are now friends.",
        "friend:request:new" => "%s has sent you a friend request.",
        "activity:friends:new" => "<a href='%s'>%s</a> and <a href='%s'>%s</a> are now friends.",
        "access_handler:friends" => "Friends",
        "your_friends" => "Your Friends",
        "friends" => "Friends",
        "friends_blogs" => "Friends' Blogs",
        "friend_request" => "Friend Request",
        "friend_requests" => "Friend Requests",
        "friendship_requested" => "Friendship Requested",
        "search_friends" => "Search Friends",
        "search_members_friends" => "Search Members Friends",
        "find_friends" => "Find Friends",
        "invite_your_friends" => "Invite Your Friends",
        "latest_friends_activity" => "Latest Friends' Activity",
        "remove_friend" => "Remove Friend",
        "add_friend" => "Add Friend"
    );

    function __construct() {
        parent::__construct("en", $this->english);
    }

}

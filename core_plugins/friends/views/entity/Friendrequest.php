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

$guid = Vars::get("guid");
$request = getEntity($guid);

$sender_guid = $request->guid_one;
$recipient_guid = $request->guid_two;

if ($sender_guid != getLoggedInUserGuid()) {
    $sender = getEntity($sender_guid);
    $avatar = $sender->icon(SMALL, "media-object");
    $url = $sender->getURL();
    $accept_url = addTokenToURL(getSiteURL(). "action/acceptFriendrequest/$guid");
    $decline_url = addTokenToURL(getSiteURL(). "action/declineFriendrequest/$guid");
    $buttons = "<div class='btn-group'>";
    $buttons .= "<a href='$accept_url' class='btn btn-success btn-xs'>Accept</a>";
    $buttons .= "<a href='$decline_url' class='btn btn-danger confirm btn-xs'>Decline</a>";
    $buttons .= "</div>";
    echo <<<HTML
<div class='panel panel-default'>
    <div class='panel-body'>
        <div class="media">
            <div class="media-left">
                <a href="$url">
                    $avatar
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><a href='$url'>$sender->full_name</a></h4>
            </div>
        </div>
    </div>
    <div class='panel-footer'>
    $buttons
    </div>
</div>
HTML;
} else {
    $recipient = getEntity($recipient_guid);
    $avatar = $recipient->icon(SMALL, "media-object");
    $url = $recipient->getURL();
    $withdraw_url = addTokenToURL(getSiteURL(). "action/withdrawFriendrequest/$guid");
    $buttons = "<a href='$withdraw_url' class='btn btn-danger btn-xs confirm'>WithDraw</a>";
    echo <<<HTML
    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class="media">
                <div class="media-left">
                    <a href="$url">
                        $avatar
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><a href='$url'>$recipient->full_name</a></h4>
                </div>
            </div>
        </div>
        <div class='panel-footer'>
            $buttons
        </div>
    </div>
HTML;
}
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
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

$guid = Vars::get('guid');
$comment = getEntity($guid);
classGateKeeper($comment, "Comment");
$owner_guid = $comment->owner_guid;
$owner = getEntity($owner_guid);
$url = $owner->getURL();
$icon = $owner->icon(SMALL);
$time = display("output/friendly_time", array(
    "timestamp" => $comment->time_created
        ));
$delete_url = addTokenToURL(getSiteURL() . "action/deleteComment/$guid");
$button = $owner_guid == getLoggedInUserGuid() ? "<a href='$delete_url' class='btn btn-danger btn-xs pull-right confirm' data-toggle='tooltip' title='Delete'><i class='fa fa-times'></i></a>" : "";
$page = <<<HTML
<li class='comment_$guid'>
    $button
    <div class="commenterImage">
        <a href="$url" title="$owner->first_name $owner->last_name">
            $icon
        </a>
    </div>
    <div class="commentText">
        <p class=""><strong><a href="$url">$owner->first_name $owner->last_name</a></strong> $comment->body</p> $time
    </div>
</li>
HTML;
echo $page;

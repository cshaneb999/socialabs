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

$guid = Vars("guid");
$button = NULL;
$group = getEntity($guid);
$icon = $group->icon(MEDIUM, "media-object");
$url = $group->getURL();
$title = $group->title;
$description = truncate($group->description);
$owner_guid = $group->owner_guid;
$owner = getEntity($owner_guid);
$by = "Created by: <a href='" . $owner->getURL() . "'>" . $owner->full_name . "</a>" . friendlytime($group->time_created);
$members = GroupsPlugin::memberCount($group);
switch ($group->membership) {
    case "public":
        $type = "Public";
        break;
    case "friends":
        $type = "Friends";
}
if (GroupsPlugin::loggedInUserCanJoin($group)) {
    $join_url = getSiteURL() . "action/joinGroup/$guid";
    $join_url = addTokenToURL($join_url);
    $button = "<p style='margin-top:10px;'><a href='$join_url' class='btn btn-success btn-sm'>Join Group</a></p>";
}
echo <<<HTML
<div class='panel panel-default'>
    <div class='panel-body'>
        <div class="media">
            <div class="media-left">
                <a href="$url">
                    $icon
                </a>
            </div>
            <div class="media-body">
                
                <h4 class="media-heading"><a href='$url'>$title</a></h4>
                $description<br/>
                $by<br/>
                $button
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div style='font-size:12px;' class='label label-info'>Access Level: $type</div>
        <div style='font-size:12px;' class='label label-success'>$members Member(s)</div>
    </div>
</div>
HTML;

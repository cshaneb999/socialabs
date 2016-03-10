<?php

/**
 * User Entity
 *
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
$view_type = Vars::get("view_type");
$class = NULL;
$user = getEntity($guid);
$url = $user->getURL();
$footer = display("user/buttons");
$member_body = display("user/body", array(
    "guid" => $guid
        ));
switch ($view_type) {
    case "list":
    default:
        $timeago = display("output/friendly_time", array(
            "timestamp" => $user->time_created
        ));
        $icon = $user->icon(MEDIUM, "media-object");
        echo <<<HTML
<button class="list-group-item member_list_element $class" data-guid="$guid">
    <span class="media">
        <span class="media-left">
            $icon
        </span>
        <span class="media-body">
            <strong>$user->full_name</strong><br/>
            Member since:<br/>$timeago
        </span>
    </span>
    <span class='view_full_profile'>Click To View Full Profile</span>
</button>
HTML;
        break;
    case "gallery":
        $icon = $user->icon(MEDIUM, "img-responsive");
        echo <<<HTML
        <div class='avatar_gallery'>
            <a href='$url' data-toggle='tooltip' title='$user->full_name'>$icon</a>
        </div>
HTML;
        break;
}


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
$chat_message = getEntity($guid);
$owner_guid = $chat_message->owner_guid;
$owner = getEntity($owner_guid);
$icon = $owner->icon(SMALL, "media-object");
$url = $owner->getURL();
if (getLoggedInUserGuid() == $owner_guid) {
    echo <<<HTML
<div class="well well-sm" style="margin-top:10px;">
    <div class="media">
        <div class="media-body">
            <small>$chat_message->text</small>
        </div>
        <div class='media-right'>
            <a href="$url">
                $icon
            </a>
        </div>
    </div>
</div>
HTML;
} else {
    echo <<<HTML
<div class="well well-sm" style="margin-top:10px;">
    <div class="media">
        <div class="media-left">
            <a href="$url">
                $icon
            </a>
        </div>
        <div class="media-body">
            <small>$chat_message->text</small>
        </div>
    </div>
</div>
HTML;
}
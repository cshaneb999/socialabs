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

$vars = Vars::get("called_by");

$guid = pageArray(1);
$owner = getEntity($guid);

$friends = FriendsPlugin::getFriends($owner, 0, 1000);

$friends_list = viewEntityList($friends, "gallery");

$find_friends_url = getSiteURL() . "members";

$search_friends_form = drawForm(array(
    "name" => "search_friends",
    "method" => "get",
    "page" => "searchFriends",
    "class" => "form-inline"
        ));
$friends_text = translate("friends");
$find_friends_text = translate("find_friends");
$body = <<<HTML
<div class='panel panel-default'>
    <div class='panel-heading'>
        <div class='pull-right'>
            <a href='$find_friends_url' class='btn btn-success btn-xs'>$find_friends_text</a>
        </div>
        $friends_text
    </div>
    <div class='panel-body'>$friends_list</div>
    <div class='panel-footer'>$search_friends_form</div>
</div>
HTML;
echo $body;

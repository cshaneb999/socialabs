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
$view_type = Vars::get("view_type");
$topic = getEntity($guid);
$description = display("output/editor", array(
    "value" => $topic->description
        ));
switch ($view_type) {
    default:
    case "list":
        $description = truncate($description);
        break;
    case "entity":
        break;
}
$comments = getEntities(array(
    "type" => "Forumcomment",
    "metadata_name" => "container_guid",
    "metadata_value" => $guid,
    "count" => true
        ));
$url = $topic->getURL();
$edit_url = getSiteURL() . "forum/editTopic/$guid";
$delete_url = addTokenToURL(getSiteURL() . "action/deleteTopic/$guid");
echo <<<HTML
<div class='panel panel-default'>
    <div class='panel-heading'>
HTML;
if (adminLoggedIn()) {
    echo <<<HTML
        <span class='pull-right btn-group'>
            <a href='$edit_url' class='btn btn-info btn-sm'>Edit</a>
            <a href='$delete_url' class='btn btn-danger btn-sm confirm'>Delete</a>
        </span>
HTML;
}
echo <<<HTML
        <a href='$url'><h3 class='panel-header'>$topic->title</h3></a>
    </div>
    <div class='panel-body'>
        <div class='row'>
            <div class='col-sm-12'>
                $description
            </div>
        </div>
    </div>
    <div class='panel-footer clearfix'>
        <ul class="nav nav-pills" role="tablist">
            <li class='active' role="presentation"><a href="$url">Comments <span class="badge">$comments</span></a></li>
        </ul>
    </div>
</div>
HTML;

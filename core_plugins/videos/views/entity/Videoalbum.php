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

$heading = $description = $created = NULL;

$entity = getEntity($guid);
$url = $entity->getURL();
$icon = $entity->icon(MEDIUM, "media-object");
$type = $entity->type;

$delete_url = addTokenToURL(getSiteURL() . "action/delete" . $type . "/" . $guid);

$edit_url = getSiteURL() . $type . "/edit/" . $guid;
if (isset($entity->time_created)) {
    $created = display("output/friendly_time", array(
        "timestamp" => $entity->time_created
    ));
}
if (isset($entity->description)) {
    $description = display("output/editor", array(
        "value" => truncate($entity->description)
    ));
}
if (adminLoggedIn() || $entity->owner_guid == $guid) {
    $delete_button = "<a href='$delete_url' class='btn btn-danger btn-xs'><i class='fa fa-times'></i></a>";
    $edit_button = "<a href='$edit_url' class='btn btn-info btn-xs'><i class='fa fa-pencil'></i></a>";
    $heading = <<<HTML
    <div class="panel-heading clearfix">
        <div class="pull-right">
            $edit_button
            $delete_button
        </div>
    </div>
HTML;
}
switch ($view_type) {
    default:
    case "list":
        $body = <<<HTML
<div class="panel-body">
    <div class="media">
        <div class="media-left">
            <a href="$url">
                $icon
            </a>
        </div>
        <div class="media-body">
            <address><a href='$url'><strong>$entity->title</strong></a><br/>
            $description
            $created 
            </address>
        </div>
    </div>
</div>
HTML;
        $footer = "";

        echo <<<HTML
    <div class="panel panel-default">
        $heading
        $body
        $footer
    </div>
HTML;
        break;
    case "gallery":
        $icon = $entity->icon(EXTRALARGE);
        $url = $entity->getURL();
        echo <<<HTML
        <div class='col-sm-3 masonry_element'>
            <div class='panel panel-default'>
                <div class='panel-heading'>
                    <a href='$url'>$entity->title</a>
                </div>
                <div class='panel-body'>
                    <a href="$url">
                        $icon
                    </a>
                </div>
            </div>
        </div>
HTML;
        break;
    case "videopicker_gallery":
        echo "<a title='" . $entity->title . "' data-toggle='tooltip'>";
        echo $entity->icon(MEDIUM, "videopicker_album_gallery_item well well-sm album_" . $guid);
        echo "</a>";
        break;
}


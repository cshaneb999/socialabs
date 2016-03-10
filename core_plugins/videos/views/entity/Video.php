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

$heading = NULL;

$entity = getEntity($guid);
$url = $entity->getURL();
$icon = $entity->icon(MEDIUM, "media-object");
$type = $entity->type;

$delete_url = addTokenToURL(getSiteURL() . "action/delete" . $type . "/" . $guid);
$delete_button = "<a href=''><i class='fa fa-times'></i></a>";

$edit_url = "";
$edit_button = "";

$created = display("output/friendly_time", array(
            "timestamp" => $entity->time_created
        ));
if ($entity->description) {
    $description = display("output/editor", array(
                "value" => truncate($entity->description)
    ));
}
if (adminLoggedIn() || $entity->owner_guid == $guid) {
    $delete_url = "";
    $delete_button = "<a href='$delete_url' class='btn btn-danger btn-xs'><i class='fa fa-times'></i></a>";
    $edit_url = "";
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
            $footer = "<a class='btn btn-success' href='$url'>View</a>";

            echo <<<HTML
<div class="col-sm-3 masonry_element">
    <div class="panel panel-default">
        $heading
        $body
        $footer
    </div>
</div>
HTML;
            break;
        case "gallery":
            $icon = $entity->icon(LARGE);
            $url = $entity->getURL();
            echo <<<HTML
        <div class='col-sm-3 masonry_element'>
            <div class='panel panel-default'>
                <div class='panel-heading'>
                    $entity->title
                </div>
                <div class='panel-body'>
                    <center>
                        <a href="$url">
                            $icon
                        </a>
                    </center>
                </div>
            </div>
        </div>
HTML;
            break;
        case "non_isotope_list":
            $icon = $entity->icon(LARGE);
            $owner_guid = $entity->owner_guid;
            $owner = getEntity($owner_guid);
            $owner_url = $owner->getURL();
            $entity_url = $entity->getURL();
            echo <<<HTML
        <div class='panel panel-default'>
            <div class='panel-body'>
        <center>
            <a href="$url">
                $icon
            </a>
                </center>
                </div>
                <div class='panel-footer clearfix'>
                    <a href='$entity_url' class='btn btn-xs btn-success pull-right'>View</a>
                $entity->title<br/>
                <small>Added by: <a href='$owner_url'>$owner->full_name</a></small>
                </div>
        </div>
HTML;
            break;
        case "videopicker_gallery":
            echo "<div class='col-sm-3'>";
            echo $entity->icon(MEDIUM, "img-responsive well well-sm videopicker_gallery_item video_$guid");
            echo "</div>";
            break;
}


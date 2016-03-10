<?php

/**
 * File Entity
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
$buttons = NULL;

$item_class = Vars::get("item_class");
if (!$item_class) {
    $item_class = "avatar_gallery";
};
$link = Vars::get("link");

$file = getEntity($guid);

classGateKeeper($file, "File");

$url = getSiteURL(). $file->getURL();
$owner_guid = $file->owner_guid;
$owner = getEntity($owner_guid);
$name = "<a href='" . $owner->getURL() . "'>" . $owner->first_name . " " . $owner->last_name . "</a>";

$view_type = Vars::get("view_type");
if (!$view_type) {
    $view_type = "list";
}
$size = Vars::get("size");
if (!$size) {
    $size = MEDIUM;
}

$created = "Uploaded: by " . $name . " " . display("output/friendly_time", array("timestamp" => $file->time_created));

$icon = Image::getImageURL($file->guid, $size);

$body_before = display("file/body_before", array(
    "guid" => $guid
        ));

$body_after = display("file/body_after", array(
    "guid" => $guid
        ));
$media_left_before = display("file:list:media:left:before", array(
    "guid" => $guid
        ));
$media_left_after = display("file:list:media:left:after", array(
    "guid" => $guid
        ));
$media_right_before = display("file:list:media:right:before", array(
    "guid" => $guid
        ));
$media_right_after = display("file:list:media:right:after", array(
    "guid" => $guid
        ));
if (getLoggedInUserGuid() == $file->owner_guid) {
    $buttons = "<a href='" . addTokenToURL(getSiteURL(). 'action/deleteFile/' . $guid) . "' class='btn btn-danger confirm'><i class='fa fa-times'></i></a>";
}
$filename = $file->filename;
switch ($view_type) {
    case "list":
    default:
        $core_output = <<<HTML
<div class="well well-sm">
    <div class="media">
        <input type="hidden" class="guid" value="$guid"/>
        <div class="media-left">
            $media_left_before
            <a href="$url" title="$file->title" data-toggle="tooltip" >
                <img class="media-object img-rounded" data-title="$file->title" title="$file->title" src="$icon" style='width:64px;' alt="$file->title">
            </a>
            $media_left_after
        </div>
        <div class="media-body">
            $body_before
            <h4 class="media-heading"><a href="$url">$file->title</a></h4>
            $created
            $body_after
        </div>
        <div class="media-right">
            $media_right_before
            $buttons
            $media_right_after
        </div>
    </div>
</div>
HTML;
        break;
    case "gallery":
        $core_output = <<<HTML
<div class="$item_class">
    <input type="hidden" class="guid" value="$guid"/>
    <a href="$url" title="$file->name" data-toggle="tooltip">
        <img alt='' src="$icon" title="$file->name" class="avatar img-responsive img-rounded $size"/>
    </a>
</div >
HTML;
        break;
    case "filepicker_gallery":
        $view_file = display("output/file", array("guid" => $guid, "thumbnail" => 50, "show_title" => "true"));
        $core_output = <<<HTML
<div class='pull-left'>
    <div class='well well-sm filepicker clearfix'>
        <input type='hidden' name='guid' value='$guid'/>
        $view_file
    </div>
</div>
HTML;
        break;
    default:
        $core_output = display($view_type, array(
            "guid" => $guid
        ));
        break;
}
echo (isset($core_output) ? $core_output : "");

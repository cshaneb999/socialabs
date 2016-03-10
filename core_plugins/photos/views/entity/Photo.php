<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$guid = Vars::get("guid");
$view_type = Vars::get("view_type");
$photo = getEntity($guid);
switch ($view_type) {
    case "gallery":
    default:
        $buttons = NULL;
        $url = $photo->getURL();
        $icon = $photo->icon(EXTRALARGE, "img-responsive");
        if ($photo->owner_guid == getLoggedInUserGuid()) {
            $delete_url = addTokenToURL(getSiteURL(). "action/deletePhoto/$guid");
            $buttons = <<<HTML
                    <div class='btn-group pull-right'>
                        <a href='$delete_url' class='btn btn-danger btn-xs confirm'>Delete</a>
                    </div>
HTML;
        }
        echo <<<HTML
<div class='col-lg-3 masonry_element'>
    <div class='panel panel-default'>
        <div class='panel-heading clearfix'>
            $buttons
            $photo->title 
        </div>
        <div class='panel-body'>
            <a href='$url'>
                $icon
            </a>
        </div>
    </div>
</div>
HTML;

        break;
    case "non_isotope_list":
        $icon = $photo->icon(LARGE);
        $owner_guid = $photo->owner_guid;
        $owner = getEntity($owner_guid);
        $owner_url = $owner->getURL();
        $photo_url = $photo->getURL();
        echo <<<HTML
        <div class='panel panel-default'>
            <div class='panel-body'>
                <center>
                    <a href="$photo_url">
                        $icon
                    </a>
                </center>
            </div>
            <div class='panel-footer clearfix'>
                <a href='$photo_url' class='btn btn-xs btn-success pull-right'>View</a>
                $photo->title<br/>
                <small>Added by: <a href='$owner_url'>$owner->full_name</a></small>
                </div>
        </div>
HTML;
        break;
    case "photopicker_gallery":
        echo $photo->icon(MEDIUM, "well well-sm photopicker_gallery_item photo_" . $guid);
        break;
}



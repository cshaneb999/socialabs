<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$guid = Vars::get("guid");
$activity_entity = getEntity($guid);
$owner_guid = $activity_entity->owner_guid;
$owner = $icon = $owner_url = NULL;
$comments = display("output/inline_comments", array(
    "guid" => $guid
        ));
if ($owner_guid) {
    $owner = getEntity($owner_guid);
    $icon = $owner->icon(MEDIUM, "media-object");
    $owner_url = $owner->getURL();
    $timeago = display("output/friendly_time", array(
        "timestamp" => $activity_entity->time_created
    ));

    $text = translate($activity_entity->text, $activity_entity->params);
}

echo <<<HTML
<div class="well well-sm">
    <div class="media">
      <div class="media-left">
        <a href="$owner_url">
          $icon
        </a>
      </div>
      <div class="media-body">
        $text
        <p>$timeago</p>
        $comments
      </div>
    </div>
</div>
HTML;

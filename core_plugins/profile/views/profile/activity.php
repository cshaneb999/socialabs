<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$activity_url = getSiteURL() . "activity";
$guid = Vars("guid");
$user = getEntity($guid);
if (isEnabledPlugin("Friends")) {
    $friends = FriendsPlugin::getFriendGuidCSString($user);
}
if (isset($friends)) {
    $params = array(
        "type" => "Activity",
        "order_by" => "time_created",
        "order_reverse" => true,
        "limit" => 8,
        "metadata_name" => "owner_guid",
        "metadata_value" => "(" . $friends . ")",
        "operand" => "IN"
    );
    echo display("ajax/entity_list", array(
        "params" => $params,
        "wrapper" => "ul",
        "wrapper_class" => "timeline timeline-small",
        "title" => translate("latest_friends_activity"),
        "id" => "ajax_friends_activity",
        "panel" => true
    ));
}
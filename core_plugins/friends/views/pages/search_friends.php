<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$users = false;
$guid = getInput("guid");
$filter = getInput("filter");
if (!$filter) {
    $friends = listEntities(array(
        "type" => "Relationship",
        "metadata_name_value_pairs" => array(
            array(
                "name" => "guid_one",
                "value" => $guid
            ),
            array(
                "name" => "relationship_type",
                "value" => "friend"
            )
        )
    ));
} else {
    $filter = strtolower($filter);
    $friends = getEntities(array(
        "type" => "Relationship",
        "metadata_name_value_pairs" => array(
            array(
                "name" => "guid_one",
                "value" => $guid
            ),
            array(
                "name" => "relationship_type",
                "value" => "friend"
            )
        )
    ));
}
if ($friends) {
    foreach ($friends as $friend) {
        if ($filter) {
            $user2_guid = $friend->guid_two;
            $user2 = getEntity($user2_guid);
            $full_name = strtolower($user2->full_name);
            if (strpos($full_name, $filter) !== false) {
                $users[] = getEntity($friend->guid_two);
            }
        } else {
            $users[] = getEntity($friend->guid_two);
        }
    }
    if ($users) {
        echo viewEntityList($users);
    }
}
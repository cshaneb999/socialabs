<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$user_guid = pageArray(1);
if (!$user_guid) {
    $user_guid = Vars("guid");
    if (!$user_guid) {
        $user_guid = getLoggedInUserGuid();
    } else {
        $user = getEntity($user_guid);
        if (!is_a($user, "SociaLabs\User")) {
            $user_guid = getLoggedInUserGuid();
        }
    }
}
if ($user_guid) {
    $user = getEntity($user_guid);
    $status_html = display("output/editor", array(
        "value" => $user->status
    ));
    echo "<blockquote>";
    echo $status_html;
    echo "</blockquote>";
}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$guid = Vars::get("guid");
$user = getEntity($guid);
$joined = display("output/friendly_time", array(
    "timestamp" => $user->time_created
        ));
echo "<p>Joined:$joined</p>";
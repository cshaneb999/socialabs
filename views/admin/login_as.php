<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$guid = Vars::get("guid");
if ($guid != getLoggedInUserGuid()) {
    $url = addTokenToURL(getSiteURL() . "action/loginas/$guid");
    echo "<a href='$url' class='btn btn-danger btn-xs'>Login As</a>";
}
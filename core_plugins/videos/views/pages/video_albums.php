<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$guid = pageArray(1);
if (!$guid) {
    $guid = getLoggedInUserGuid();
}
$albums = listEntities(array(
    "type" => "Videoalbum",
    "metadata_name" => "owner_guid",
    "metadata_value" => $guid,
    "view_type" => "gallery"
        ));
echo $albums;

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$body = drawForm(array(
    "name" => "video_settings",
    "method" => "post",
    "action" => "VideoSettings"
        ));
$header = "Video Settings";
echo display("page_elements/page_header", array(
    "text" => $header
));
echo $body;

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

require_once (dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php";

$guid = getInput("guid");
$path = getInput("path");
$file_location = getDataPath() . "videos" . "/" . $guid . "/" . "video.$path";
if (file_exists($file_location)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file_location);
    $size = filesize($file_location);
    
    echo FileSystem::serveFilePartial($file_location, NULL, $mime);
}
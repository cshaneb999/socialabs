<?php

namespace SociaLabs;

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
$guid = getInput("guid");
if (!$guid) {
    $guid = Vars::get("guid");
}
$file = getEntity($guid);
$thumbnail = getInput("thumbnail");
if (!$thumbnail) {
    $thumbnail = Vars::get("thumbnail");
}
if (!$thumbnail) {
    $thumbnail = HUGE;
}
$file_location = getDataPath() . "files" . "/" . $file->guid . "/" . "thumbnail" . "/" . $thumbnail . "/" . $file->filename;
if (file_exists($file_location)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file_location);
    $size = filesize($file_location);
    header('Content-Type:' . $mime);
    header("Content-length: $size");
    readfile($file_location);
}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$upload_video = Setting::get("allow_video_uploads");

$youtube_url = display("input/text", array(
    "name" => "url",
    "class" => "form-control",
    "value" => NULL,
    "label" => NULL
        ));


if ($upload_video == "yes") {
    echo "<label>Paste a youtube URL, or upload a new video.</label>";
    $upload = display("input/file", array(
        "name" => "video_file",
        "class" => "form-control",
        "value" => NULL,
        "label" => NULL
    ));
    echo <<<HTML
<div>
    <ul class="nav nav-tabs" role="tablist" style='margin-bottom:8px;'>
        <li role="presentation" class="active"><a href=".youtube_url" aria-controls=".youtube_url" role="tab" data-toggle="tab"><i class='fa fa-youtube'></i> Youtube URL</a></li>
        <li role="presentation"><a href=".upload" aria-controls="upload" role="tab" data-toggle="tab"><i class='fa fa-upload'></i> Upload</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active youtube_url" id="youtube_url">
            $youtube_url
        </div>
        <div role="tabpanel" class="tab-pane upload">
            $upload
            <div class='bar-loader'></div>
        </div>
    </div>
</div>
HTML;
} else {
    echo "<label>Paste a youtube URL.</label>";
    echo $youtube_url;
}


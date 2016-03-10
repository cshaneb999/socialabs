<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$header = Vars::get("header");
$button = Vars::get("button");
$body = Vars::get("body");
$breadcrumbs = Vars::get("breadcrumbs");
$footer = Vars::get("footer");
if ($breadcrumbs) {
    $breadcrumbs = display("page_elements/breadcrumbs", array(
                "breadcrumbs" => $breadcrumbs
    ));
    $breadcrumbs = <<<HTML
<div class="row">
    <div class="col-sm-12">
        $breadcrumbs
    </div>
</div>
HTML;
} else {
    $breadcrumbs = NULL;
}

$wrapper_class = Vars::get("wrapper_class");
if ($wrapper_class) {
    $wrapper_start = "<div class='$wrapper_class'>";
    $wrapper_end = "</div>";
} else {
    $wrapper_start = "";
    $wrapper_end = "";
}
if ($header || $button) {
    $header = display("page_elements/page_header", array(
                "text" => $header,
                "button" => $button
    ));
}
if ($footer) {
    $footer = <<<HTML
<div class="container">
    <div class="row">
        $footer
    </div>
</div>
HTML;
}
echo <<<HTML
<div class="container">
    $breadcrumbs
    <div class='row clearfix'>
        <div class='col-sm-12'>
            $header
        </div>
    </div>
    $wrapper_start
        $body
    $wrapper_end
    $footer
</div>
HTML;

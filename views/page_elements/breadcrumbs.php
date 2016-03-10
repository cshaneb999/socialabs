<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$breadcrumbs = Vars::get("breadcrumbs");
$class = NULL;
if (currentPage() == "home") {
    $class = "active";
}
$list = "<li class='$class'><a href='" . getSiteURL() . "'>Home</a></li>";
if (!empty($breadcrumbs)) {
    foreach ($breadcrumbs as $breadcrumb) {
        if (isset($breadcrumb['active'])) {
            $class = "active";
        } else {
            $class = NULL;
        }
        $list .= "<li class='$class'><a href='" . $breadcrumb['link'] . "'>" . $breadcrumb['label'] . "</a></li>";
    }
}
echo <<<HTML
    <ol class="breadcrumb">
        $list
    </ol>
HTML;

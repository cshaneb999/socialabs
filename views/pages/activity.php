<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$offset = getInput("offset", 0);
$limit = 10;
$activity = listEntities(array(
            "type" => "Activity",
            "order_by" => "time_created",
            "order_reverse" => true,
            "offset" => $offset,
            "limit" => $limit
        ));
$count = getEntities(array(
            "type" => "Activity",
            "count" => true
        ));
$body = <<<HTML
<ul class="timeline clearfix">
    $activity
</ul>
HTML;
$footer = display("page_elements/pagination", array(
            "count" => $count,
            "offset" => $offset,
            "limit" => $limit,
            "url" => "activity"
        ));
echo drawPage(array(
    "header" => "Sitewide Activity",
    "body" => $body,
    "footer" => $footer
));

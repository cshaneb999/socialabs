<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$offset = getInput("offset", 0);

$categories = listEntities(array(
            "type" => "Forumcategory",
            "limit" => 5,
            "offset" => $offset
        ));
$count = getEntities(array(
            "type" => "Forumcategory",
            "count" => true
        ));
$pagination = display("page_elements/pagination", array(
            "count" => $count,
            "limit" => 5,
            "offset" => $offset,
            "url" => "forum"
        ));
echo <<<HTML
<div class='row'>
    <div class='col-sm-12'>
        $categories
        $pagination
    </div>
</div>
HTML;

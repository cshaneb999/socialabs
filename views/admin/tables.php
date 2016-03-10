<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$content = NULL;
$tables = Dbase::getAllTables(false);
foreach ($tables as $key => $name) {
    if ($name != "entities") {
        $purge_url = addTokenToURL(getSiteURL() . "action/purgeTable/$name");
        $delete_url = addTokenToURL(getSiteURL() . "action/deleteTable/$name");
        $query = "SELECT COUNT(*) FROM `$name`";
        $results = Dbase::getResultsArray($query);
        if ($results) {
            $records = $results[0]['COUNT(*)'];
            $buttons = "<a href='$purge_url' class='btn btn-warning btn-xs confirm'>Purge</a>";
            if ($name != "User") {
                $buttons .= "<a href='$delete_url' class='btn btn-danger btn-xs confirm'>Delete</a>";
            }
            $content .= <<<HTML
<tr>
    <td>$name</td>
    <td>$records</td>
    <td>$buttons</td>
</tr>

HTML;
        }
    }
}

$body = <<<HTML
<table class='table table-striped table-bordered table-hover'>
    <tr>
        <th>Table Name</th>
        <th>Records</th>
        <th>Actions</th>
    </tr>
    $content
</table>
HTML;

$header = "Tables";
echo display("page_elements/page_header", array(
    "text" => $header
));
echo $body;

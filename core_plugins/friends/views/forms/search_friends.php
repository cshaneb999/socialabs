<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

echo display("input/hidden", array(
    "name" => "guid",
    "value" => pageArray(1)
));

echo display("input/text", array(
    "name" => "filter",
    "class" => "form-control input-sm",
    "placeholder" => translate("search_members_friends"),
    "value" => NULL,
    "label" => NULL
));

echo display("input/submit", array(
    "class" => "btn btn-success btn-sm",
    "label" => "Search"
));

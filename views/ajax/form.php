<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$name = Vars::get("form_name");
$action = Vars::get("action");
$inputs = Vars::get("inputs");
$class = Vars::get("class");
$method = Vars::get("method");

echo drawForm(array(
    "name" => $name,
    "action" => $action,
    "inputs" => $inputs,
    "class" => $class,
    "method" => $method
));

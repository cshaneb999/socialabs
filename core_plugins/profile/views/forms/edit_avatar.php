<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

echo display("input/file", array(
    "name" => "avatar"
));
echo display("input/submit", array(
    "label"  => "Upload",
    "class"  => "btn btn-success",
    "cancel" => true
));

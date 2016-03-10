<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {
    echo display("input/text", array(
        "name" => "title",
        "label" => "Category Title",
        "value" => NULL,
        "class" => "form-control",
        "required" => true
    ));
    echo display("input/editor", array(
        "name" => "description",
        "label" => "Category Description",
        "value" => NULL,
        "class" => "form-control"
    ));
    echo display("input/text", array(
        "label" => "Sorting Order",
        "name" => "order",
        "value" => 500
    ));
    echo display("input/submit", array(
        "class" => "btn btn-success",
        "label" => "Save"
    ));
}
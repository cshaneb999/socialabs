<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {
    echo display("input/text", array(
        "name" => "confirm_deletion",
        "class" => "form-control",
        "label" => "To delete all site date, type 'DELETE ALL DATA' without the quotes in the box below and click submit."
    ));
    echo display("input/submit", array(
        "label" => "Submit",
        "class" => "btn btn-danger"
    ));
}
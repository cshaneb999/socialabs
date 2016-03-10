<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

$user = getLoggedInUser();
$user_type = (isset($user->profile_type) ? $user->profile_type : "default");
$fields = ProfileField::get($user_type);
if ($fields) {
    foreach ($fields as $key => $field) {
        $fieldname = $key;
        $value = (isset($user->$fieldname) ? $user->$fieldname : "");
        if ($value && ($field['field_type'] == "date")) {
            $value = date("m/d/Y", $value);
        }
        $options = (isset($field['options']) ? $field['options'] : "");
        $class = (isset($field['class']) ? $field['class'] : "form-control");
        echo display("input/" . $field['field_type'], array(
            "name" => $key,
            "value" => $value,
            "label" => $field['label'],
            "options_values" => $options,
            "class" => $class
        ));
    }
}
echo display('input/submit', array(
    'label' => 'Save',
    "class" => "btn btn-success",
    "cancel" => true
));

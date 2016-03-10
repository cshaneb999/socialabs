<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {
    $access = getIgnoreAccess();
    setIgnoreAccess();
    $system_settings = Setting::getAll("video_settings");
    setIgnoreAccess($access);
    if ($system_settings) {
        foreach ($system_settings as $name => $setting) {
            echo display("input/" . $setting->field_type, array(
                "name" => $setting->name,
                "value" => $setting->value,
                "class" => "form-control",
                "label" => isset($setting->label) ? $setting->label : translate("admin:video_settings:" . $setting->name),
                "options_values" => $setting->options
            ));
        }
        echo display("input/submit", array(
            "class" => "btn btn-success",
            "label" => "Save"
        ));
    } else {
        echo "<blockquote>These settings are created by plugins.</blockquote>";
    }
}
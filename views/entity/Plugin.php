<?php

/**
 * Plugin Entity
 *
 * PHP version 5
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 SociaLabs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 *
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

adminGateKeeper();

$requirements = "";
$guid = Vars::get('guid');
$plugin = getEntity($guid);
classGateKeeper($plugin, "Plugin");
if ($plugin->requires) {
    if (isset($plugin->requires) && is_array($plugin->requires)) {
        foreach ($plugin->requires as $required_plugin) {
            $required_plugin_entity = Plugin::getPluginByName($required_plugin);
            if ($required_plugin_entity) {
                if ($required_plugin_entity->status == "enabled") {
                    $requirements .= "<span class='enabled_plugin_link' onclick='sitejs.gotoplugin($required_plugin_entity->guid)'>$required_plugin</span> , ";
                } else {
                    $requirements .= "<span class='disabled_plugin_link' onclick='sitejs.gotoplugin($required_plugin_entity->guid)'>$required_plugin</span> , ";
                }
            }
        }
    }
    $requirements = substr($requirements, 0, -3);
}
$enable_plugin_url = addTokenToURL(getSiteURL() . "action/enablePlugin/$guid");
$disable_plugin_url = addTokenToURL(getSiteURL() . "action/disablePlugin/$guid");
if ($plugin->status == "disabled") {
    $link = "<a href='$enable_plugin_url' class='pull-right btn btn-xs btn-success'><i class='fa fa-plus-circle'></i> Enable</a>";
    $class = "plugin-disabled";
    echo <<<HTML
<li class="alert alert-danger $class" id="guid_$guid" style="cursor:move;">
    $link
    <p><strong>$plugin->label</strong></p>
    <p>Requires:  $requirements</p>
</li>
HTML;
} else {
    $link = "<a href='$disable_plugin_url' class='pull-right btn btn-xs btn-danger'><i class='fa fa-minus-circle'></i> Disable</a>";
    $class = "plugin-enabled";
    echo <<<HTML
<li class="alert alert-success $class" id="guid_$guid" style="cursor:move;">
    $link
    <p><strong>$plugin->label</strong></p>
    <p>Requires:  $requirements</p>
</li>
HTML;
}

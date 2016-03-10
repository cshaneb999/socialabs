<?php

/**
 * Plugins Admin Tab
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

Plugin::getPluginsFromFileSystem();

$access = getIgnoreAccess();
setIgnoreAccess();
$plugin_array = getEntities(array(
    "type" => "Plugin",
    "order_by" => "plugin_order"
        ));

setIgnoreAccess($access);
$plugins = viewEntityList($plugin_array);

$body = <<<HTML
<ul class="sortable-list" style="list-style:none;margin-left:0px;padding-left:0px;">
    $plugins
</ul>
HTML;
$header = "Plugins";
$url = addTokenToURL(getSiteURL() . "action/EnableAllPlugins");
$url2 = addTokenToURL(getSiteURL() . "action/DisableAllPlugins");
$button = "<a href='$url' class='btn btn-warning confirm'>Enable All Plugins</a>";
$button .= "<a href='$url2' class='btn btn-danger confirm'>Disable All Plugins</a>";
echo display("page_elements/page_header", array(
    "text" => $header,
    "button" => $button
));
echo $body;

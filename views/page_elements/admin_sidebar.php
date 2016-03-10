<?php

/**
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
$admin_tabs = Admintab::get();
$tab = pageArray(1);

if (!$tab) {
    $tab = "general";
}

$url = getSiteURL();
$button = "";
$page_body = "<ul class='nav nav-pills nav-stacked'>";

foreach ($admin_tabs as $admin_tab) {
    $name = $admin_tab->name;
    $class = ($tab == $name ? "active" : "");
    $page_body .= "<li role='presentation' class='$class'>";
    $page_body .= "<a href='{$url}admin/{$name}'>" . translate("admin_panel:" . $admin_tab->name) . "</a>";
    $page_body .= "</li>";

    if ($admin_tab->name == $tab) {
        if ($admin_tab->buttons) {
            $button = "<div class='btn-group'>";
            foreach ($admin_tab->buttons as $tab_button) {
                $href = $tab_button['href'];
                $class = $tab_button['class'];
                $label = $tab_button['label'];
                $button .= "<a href='$href' class='$class' style='margin-bottom:20px;'>$label</a>";
            }
            $button .= "</div>";
        }
    }
}
$page_body .= "</ul>";
echo $page_body;

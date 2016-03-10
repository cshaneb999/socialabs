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

class GroupsPageHandler extends PageHandler {

    public function __construct() {
        $title = $body = $button = NULL;
        switch (pageArray(1)) {
            case "all":
            default:
                if (loggedIn()) {
                    $admin_groups = Setting::get("admin_groups");
                    if (!$admin_groups) {
                        $admin_groups = "users";
                    }
                    if (($admin_groups == "admin" && adminLoggedIn()) || $admin_groups == "users") {
                        $button = "<a href='" . getSiteURL() . "groups/create' class='btn btn-success'>Create a Group</a>";
                    }
                }
                $body = display("pages/groups");
                break;
            case "create":
                $admin_groups = Setting::get("admin_groups");
                if (!$admin_groups) {
                    $admin_groups = "users";
                }
                if (($admin_groups == "admin" && adminLoggedIn()) || $admin_groups == "users") {
                    $title = "Create a Group";
                    $body = drawForm(array(
                        "name" => "create_group",
                        "action" => "createGroup",
                        "method" => "post",
                        "files" => true
                    ));
                }
                break;
            case "view":
                $guid = pageArray(2);
                $group = getEntity($guid);
                $edit_url = getSiteURL() . "groups/edit/$guid";
                $delete_url = addTokenToURL(getSiteURL() . "action/deleteGroup/$guid");
                if ($group->ownerIsLoggedIn()) {
                    $button = "<a href='$edit_url' class='btn btn-warning'>Edit Group</a>";
                    $button .= "<a href='$delete_url' class='btn btn-danger confirm'>Delete Group</a>";
                }
                $title = $group->title;
                $body = display("pages/group");
                break;
            case "edit":
                $guid = pageArray(2);
                $group = getEntity($guid);
                $title = "Edit " . $group->title;
                $body = drawForm(array(
                    "name" => "edit_group",
                    "action" => "editGroup",
                    "method" => "post",
                    "files" => true
                ));
                break;
        }
        $this->html = drawPage(array(
            "header" => $title,
            "body" => $body,
            "button" => $button
        ));
    }

}

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

class BlogsPlugin {

    public function __construct() {
        new Setting("who_can_create_blogs", "dropdown", array(
            "everyone" => "Everyone",
            "admin_only" => "Admin Only"
                ), "general", "everyone");
        new MenuItem(array(
            "name" => "blogs",
            "label" => "<i class='icon ion-edit'></i><p>Blogs</p>",
            "page" => "blogs"
        ));
        new StorageType("blog", "title", "text");
        new StorageType("blog", "description", "longtext");
        if ((pageArray(0) == "blogs" && (pageArray(1) == "add" || pageArray(1) == "edit")) || pageArray(0) == 'home' && pageArray(1) == false) {
            new ViewExtension("tinymce/buttons", "blogs/tinymce");
        } else {
            ViewExtension::remove("tinymce/buttons", "blogs/tinymce");
        }
        if (isEnabledPlugin("groups")) {
            new ViewExtension("groups/right", "blogs/group_blogs");
        } else {
            removeViewExtension("groups/right", "blogs/group_blogs");
        }
    }

    static function userCanCreateBlog($user = false) {
        if (!loggedIn()) {
            return false;
        }
        if (!$user) {
            $user = getLoggedInUser();
        }
        $setting = Setting::get("who_can_create_blogs");
        if (!$setting) {
            $setting = "everyone";
        }
        switch ($setting) {
            case "everyone":
                return true;
                break;
            case "admin":
                if (adminLoggedIn()) {
                    return true;
                }
                break;
        }
        return false;
    }

}

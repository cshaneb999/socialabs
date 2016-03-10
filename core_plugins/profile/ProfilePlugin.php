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

class ProfilePlugin {

    public function __construct() {
        if (loggedIn()) {
            new MenuItem(array(
                "name" => "profile",
                "label" => "My Profile",
                "page" => "profile/" . getLoggedInUserGuid(),
                "menu" => "my_account",
                "weight" => 0
            ));
            if (currentPage() == "profile" && pageArray(1) == loggedIn()) {
                new MenuItem(array(
                    "name" => "edit_profile",
                    "label" => "Edit Profile",
                    "menu" => "profile",
                    "page" => "editProfile",
                    "link_class" => "list-group-item"
                ));
            } elseif (currentPage() == "home" && loggedIn()) {
                new MenuItem(array(
                    "name" => "view_my_profile",
                    "label" => "View My Profile",
                    "menu" => "profile",
                    "page" => "profile/" . getLoggedInUserGuid(),
                    "weight" => 10
                ));
                new MenuItem(array(
                    "name" => "edit_profile",
                    "label" => "Edit My Profile",
                    "menu" => "profile",
                    "page" => "editProfile",
                    "weight" => 20
                ));
                if (isEnabledPlugin("members")) {
                    new MenuItem(array(
                        "name" => "members",
                        "label" => "Browse Members",
                        "menu" => "profile",
                        "page" => "members",
                        "weight" => 30
                    ));
                }
                if (isEnabledPlugin("inviteFriends")) {
                    new MenuItem(array(
                        "name" => "invite_friends",
                        "label" => translate("invite_your_friends"),
                        "menu" => "profile",
                        "page" => "members",
                        "weight" => 40
                    ));
                }
            }
            if (currentPage() == "profile" && adminLoggedIn()) {
                if (adminLoggedIn()) {
                    $guid = pageArray(1);
                    $user = getEntity($guid);
                    if (is_a($user, "SociaLabs\\User")) {
                        if (!isAdmin($user)) {
                            new MenuItem(array(
                                "name" => "delete",
                                "label" => "Delete User",
                                "page" => "action/deleteUser/$guid",
                                "menu" => "profile",
                                "weight" => 100000,
                                "list_class" => "active",
                                "link_class" => "list-group-item list-group-item-danger confirm"
                            ));
                            new MenuItem(array(
                                "name" => "login_as",
                                "label" => "Login As",
                                "page" => "action/loginas/$guid",
                                "menu" => "profile",
                                "weight" => 90000,
                                "list_class" => "active",
                                "link_class" => "list-group-item list-group-item-danger confirm"
                            ));
                            if ($user->banned == "true") {
                                new MenuItem(array(
                                    "name" => "unban",
                                    "label" => "Unban",
                                    "page" => "action/unbanUser/$guid",
                                    "menu" => "profile",
                                    "weight" => 80000,
                                    "list_class" => "active",
                                    "link_class" => "list-group-item list-group-item-danger confirm"
                                ));
                            } else {
                                new MenuItem(array(
                                    "name" => "ban",
                                    "label" => "Ban",
                                    "page" => "action/banUser/$guid",
                                    "menu" => "profile",
                                    "weight" => 80000,
                                    "list_class" => "active",
                                    "link_class" => "list-group-item list-group-item-danger confirm"
                                ));
                            }
                        }
                    }
                }
            }
        }
        if (currentPage() == "profile") {
            new CSS("profile", getSitePath() . "core_plugins/profile/assets/css/profile.css");
            new FooterJS('profile', getSiteURL() . 'core_plugins/profile/assets/js/profile.js', 900, true);
        }
        if (currentPage() == "admin") {
            new ViewExtension("admin/tabs", "admin_tabs/profile_fields");
        }

        new ProfileField("first_name", "First Name", "text", false, false, "form-control", "default", 10);
        new ProfileField("last_name", "Last Name", "text", false, false, "form-control", "default", 20);
        new ProfileField("title", "Title", "text");
        new ProfileField("gender", "Gender", "dropdown", array(
            "Male" => "Male",
            "Female" => "Female"
        ));
        new ProfileField("address", "Location", "location");
        new ProfileField("lat", "", "hidden");
        new ProfileField("lng", "", "hidden");
        new ProfileField("formatted_address", "", "hidden");
        new ProfileField("country", "", "hidden");
        new ProfileField("postal_code", "", "hidden");
        new ProfileField("birthday", "Birthday", "date");
        new ProfileField("about", "About Me", "editor", false, false, "form-control", "default", 30);
        new ProfileField("interests", "Interests", "tags");
        new ProfileField("hobbies", "Hobbies", "tags");
        new StorageType("User", "about", "text");
        new StorageType("User", "address", "text");
        new StorageType("User", "formatted_address", "text");
        new StorageType("User", "interests", "text");
        new StorageType("User", "hobbies", "text");
        new ViewExtension("profile/right", "profile/activity");
    }

}

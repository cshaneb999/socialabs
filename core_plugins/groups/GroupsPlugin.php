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

class GroupsPlugin {

    public function __construct() {
        new MenuItem(array(
            "name" => "groups",
            "label" => "Groups",
            "menu" => "directory"
        ));
        new ViewExtension("groups/left", "groups/members");
        new ViewExtension("groups/middle", "groups/posts");
        new ViewExtension("groups/right", "groups/activity");
        new Admintab("groups");
        new Setting("admin_groups", "dropdown", array(
            "users" => "User",
            "admin" => "Admin"
                ), "groups", "user");
    }

    static function loggedInUserCanJoin($group) {
        if (!is_object($group)) {
            $group = getEntity($group);
        }
        $membership = $group->membership;
        // check to see if already a member
        $is_member = getEntity(array(
            "type" => "Groupmembership",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "group",
                    "value" => $group->guid
                ),
                array(
                    "name" => "member_guid",
                    "value" => getLoggedInuserGuid()
                )
            )
        ));
        if ($is_member) {
            return false;
        }
        switch ($membership) {
            case "public":
                return true;
                break;
            case "friends":

                break;
        }
    }

    static function memberCount($group) {
        if (!is_object($group)) {
            $group = getEntity($group);
        }
        $members = getEntities(array(
            "type" => "Groupmembership",
            "metadata_name" => "group",
            "metadata_value" => $group->guid,
            "count" => true
        ));
        return $members;
    }

    static function loggedInUserIsMember($group) {
        if (!is_object($group)) {
            $group = getEntity($group);
        }
        $test = getEntity(array(
            "type" => "Groupmembership",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "member_guid",
                    "value" => getLoggedInUserGuid()
                ),
                array(
                    "name" => "group",
                    "value" => $group->guid
                )
            )
        ));
        if ($test) {
            return true;
        }
        return false;
    }

    static function getGroups($user) {
        if ($user) {
            $return = array();
            if (!is_object($user)) {
                $user = getEntity($user);
            }
            $memberships = getEntities(array(
                "type" => "Groupmembership",
                "metadata_name" => "member_guid",
                "metadata_value" => $user->guid
            ));
            if (!$memberships) {
                return array();
            }
            foreach ($memberships as $membership) {
                $group = getEntity($membership->group);
                if ($group->title) {
                    $return[] = $group;
                }
            }
            return $return;
        }
        return array();
    }

}

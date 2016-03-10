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

class CreateGroupActionHandler {

    public function __construct() {
        gateKeeper();
        $title = getInput("title");
        $description = getInput("description");
        $access_id = getInput("access_id");
        $membership = getInput("membership");

        $group = new Group;
        $group->title = $title;
        $group->description = $description;
        $group->access_id = $access_id;
        $group->membership = $membership;
        $group->owner_guid = getLoggedInUserGuid();
        $group->save();
        $group->createAvatar();

        $test = getEntity(array(
            "type" => "Groupmembership",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "group",
                    "value" => $group->guid
                ),
                array(
                    "name" => "member_guid",
                    "value" => getLoggedInUserGuid()
                )
            )
        ));
        if (!$test) {
            $group_membership = new Groupmembership;
            $group_membership->group = $group->guid;
            $group_membership->member_guid = getLoggedInUserGuid();
            $group_membership->access_id = "system";
            $group_membership->save();
        }

        new Activity(getLoggedInUserGuid(), "group:created", array(
            getLoggedInUser()->getURL(),
            getLoggedInUser()->full_name,
            $group->getURL(),
            $group->title
                ), $group->guid);

        new SystemMessage("Your group has been created.");
        forward("groups");
    }

}

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

class AddFriendActionHandler {

    public function __construct() {
        if (!pageArray(2)) {
            return false;
        }
        gateKeeper();
        $user1 = getLoggedInUser();
        $guid = pageArray(2);
        $user2 = getEntity($guid);

        if (!FriendsPlugin::friends($user1->guid, $user2->guid)) {

            // First check if the other user has already initiated a friend request
            $request = getEntity(array(
                "type" => "Friendrequest",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "guid_one",
                        "value" => $guid
                    ),
                    array(
                        "name" => "guid_two",
                        "value" => $user1->guid
                    ),
                    array(
                        "name" => "status",
                        "value" => "new"
                    )
                )
            ));

            if ($request) {
                FriendsPlugin::addFriend($user1->guid, $guid);
                forward();
            }

            // Make sure there isn't already a friend request in the system

            $test = getEntities(array(
                        "type" => "Friendrequest",
                        "metadata_name_value_pairs" => array(
                            array(
                                "name" => "guid_one",
                                "value" => $user1->guid
                            ),
                            array(
                                "name" => "guid_two",
                                "value" => $guid
                            )
                        )
            ));
            if (!$test) {
                $friend_request = new Friendrequest;
                $friend_request->guid_one = $user1->guid;
                $friend_request->guid_two = $guid;
                $friend_request->status = "new";
                $friend_request->save();
            }

            new SystemMessage("Your friend request has been sent.");
            notifyUser($guid, translate("friend:request:new", array(
                        $user1->full_name
                    )), getSiteURL(). "friendrequests");
            forward();
        }
        forward();
    }

}

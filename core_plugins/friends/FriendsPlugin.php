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

class FriendsPlugin {

    public function __construct() {
        new Accesshandler("friends");
        if (loggedIn()) {
            new MenuItem(array(
                "name" => "friends",
                "label" => translate("friends"),
                "page" => "friends",
                "menu" => "my_account",
                "weight" => 50
            ));

            new MenuItem(array(
                "name" => "friend_requests",
                "label" => translate("friend_requests"),
                "page" => "Friendrequests",
                "menu" => "my_account",
                "weight" => 100
            ));
            new Usersetting(array(
                "name" => "notify_when_friend_request_sent",
                "field_type" => "dropdown",
                "options" => array(
                    "email" => "Email",
                    "site" => "Site",
                    "both" => "Both",
                    "none" => "None"
                ),
                "tab" => "notifications",
                "default_value" => "both"
            ));
            new Usersetting(array(
                "name" => "notify_when_friend_request_accepted",
                "field_type" => "dropdown",
                "options" => array(
                    "email" => "Email",
                    "site" => "Site",
                    "both" => "Both",
                    "none" => "None"
                ),
                "tab" => "notifications",
                "default_value" => "both"
            ));
            if (currentPage() == "profile" && pageArray(1)) {
                if (pageArray(1) != getLoggedInUserGuid()) {
                    if (!FriendsPlugin::requestSent(getLoggedInUserGuid(), pageArray(1))) {
                        if (!(FriendsPlugin::friends(pageArray(1), getLoggedInUserGuid()))) {
                            new MenuItem(array(
                                "name" => "add_friend",
                                "label" => translate("add_friend"),
                                "page" => addTokenToURL("action/addFriend/" . pageArray(1)),
                                "menu" => "profile",
                                "weight" => 10,
                                "link_class" => "list-group-item list-group-item-info confirm"
                            ));
                        } else {
                            new MenuItem(array(
                                "name" => "remove_friend",
                                "label" => translate("remove_friend"),
                                "page" => addTokenToURL("action/removeFriend/" . pageArray(1)),
                                "menu" => "profile",
                                "weight" => 10,
                                "link_class" => "list-group-item list-group-item-warning confirm"
                            ));
                        }
                    } else {
                        new MenuItem(array(
                            "name" => "friend_request_sent",
                            "label" => translate("friendship_requested"),
                            "page" => "friend",
                            "menu" => "profile",
                            "weight" => 20,
                            "link_class" => "list-group-item confirm"
                        ));
                    }
                }
            }
        }
        new ViewExtension("profile/right", "friends/profile", "before");
        new ViewExtension('pages/home_stats', 'pages/friend_stats');
        new ViewExtension("user/buttons", "friends/friend_button");
    }

    static function getFriends($owner, $offset = 0, $limit = 1000, $filter = false) {
        $return = array();
        if (!$filter) {
            $query = "SELECT user.guid FROM user LEFT JOIN relationship ON user.guid=relationship.guid_one WHERE relationship.guid_two = $owner->guid AND relationship.relationship_type='friend' LIMIT $limit OFFSET $offset;";
        } else {
            $query = "SELECT user.guid FROM user LEFT JOIN relationship ON user.guid=relationship.guid_one WHERE relationship.guid_two = $owner->guid AND user.full_name LIKE '%$filter%' AND relationship.relationship_type='friend' LIMIT $limit OFFSET $offset;";
        }
        $results = Dbase::getResultsArray($query);
        foreach ($results as $result) {
            $return[] = getEntity($result['guid']);
        }
        return $return;
    }

    static function friends($guid1, $guid2) {
        $access = getIgnoreAccess();
        setIgnoreAccess();
        $relationships = getEntities(array(
            "type" => "Relationship",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_one",
                    "value" => $guid1
                ),
                array(
                    "name" => "guid_two",
                    "value" => $guid2
                ),
                array(
                    "name" => "relationship_type",
                    "value" => 'friend'
                )
            ),
            "metadata_name_value_pairs_operand" => "AND"
        ));

        setIgnoreAccess($access);
        if ($relationships) {
            return true;
        }
        return false;
    }

    static function requestSent($guid_one, $guid_two) {
        $request = getEntity(array(
            "type" => "Friendrequest",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_one",
                    "value" => $guid_one
                ),
                array(
                    "name" => "guid_two",
                    "value" => $guid_two
                ),
                array(
                    "name" => "status",
                    "value" => "new"
                )
            )
        ));
        if ($request) {
            return true;
        }
        return false;
    }

    static function addFriend($guid_one, $guid_two) {

        $test = getEntity(array(
            "type" => "Relationship",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_one",
                    "value" => $guid_one
                ),
                array(
                    "name" => "guid_two",
                    "value" => $guid_two
                ),
                array(
                    "name" => "relationship_type",
                    "value" => "friend"
                )
            )
        ));
        if (!$test) {
            $relationship = new Relationship;
            $relationship->guid_one = $guid_one;
            $relationship->guid_two = $guid_two;
            $relationship->relationship_type = "friend";
            $relationship->save();
        }

        $test = getEntity(array(
            "type" => "Relationship",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_one",
                    "value" => $guid_two
                ),
                array(
                    "name" => "guid_two",
                    "value" => $guid_one
                ),
                array(
                    "name" => "relationship_type",
                    "value" => "friend"
                )
            )
        ));
        $relationship = new Relationship;
        $relationship->guid_one = $guid_two;
        $relationship->guid_two = $guid_one;
        $relationship->relationship_type = "friend";
        $relationship->save();

        // Set all friend requests to "accepted"
        $requests = getEntities(array(
            "type" => "Friendrequest",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_one",
                    "value" => $guid_one
                ),
                array(
                    "name" => "guid_two",
                    "value" => $guid_two
                ),
                array(
                    "name" => "status",
                    "value" => "new"
                )
            )
        ));
        if ($requests) {
            foreach ($requests as $request) {
                $request->status = "accepted";
                $request->save();
            }
        }
        $requests = getEntities(array(
            "type" => "Friendrequest",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_one",
                    "value" => $guid_two
                ),
                array(
                    "name" => "guid_two",
                    "value" => $guid_one
                ),
                array(
                    "name" => "status",
                    "value" => "new"
                )
            )
        ));
        if ($requests) {
            foreach ($requests as $request) {
                $request->status = "accepted";
                $request->save();
            }
        }

        $user1 = getEntity($guid_one);
        $user2 = getEntity($guid_two);

        new Activity(getLoggedInUserGuid(), translate("activity_feed:friend:add", array(
                    $user1->getURL(),
                    $user1->full_name,
                    $user2->getURL(),
                    $user2->full_name
        )));

        notifyUser($user2->guid, translate("friend:made", array(
            $user1->first_name . " " . $user1->last_name
                )), $user1->getURL());
        new SystemMessage(translate("add:friend:success", $user2->first_name . " " . $user2->last_name));
        forward();
    }

    static function countFriendrequests() {
        $user_guid = getLoggedInUserGuid();
        $requests = getEntities(array(
            "type" => "Friendrequest",
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "guid_two",
                    "value" => $user_guid
                ),
                array(
                    "name" => "status",
                    "value" => "new"
                )
            ),
            "count" => true
        ));
        return $requests;
    }

    static function getFriendGuidCSString($owner) {
        $friends = self::getFriends($owner);
        $friend_guids = array();
        if ($friends) {
            foreach ($friends as $friend) {
                $friend_guids[] = $friend->guid;
            }
        }
        return arrayToCSSString($friend_guids);
    }

}

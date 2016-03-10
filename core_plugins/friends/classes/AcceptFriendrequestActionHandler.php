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

class AcceptFriendrequestActionHandler {

    public function __construct() {
        $request_guid = pageArray(2);
        $request = getEntity($request_guid);
        FriendsPlugin::addFriend($request->guid_one, $request->guid_two);
        $user1 = getEntity($request->guid_one);
        $user2 = getEntity($request->guid_two);
        $params = array(
            $user1->getURL(),
            $user1->full_name,
            $user2->getURL(),
            $user2->full_name
        );
        $params2 = array(
            $user2->getURL(),
            $user2->full_name,
            $user1->getURL(),
            $user1->full_name
        );
        if (getLoggedInUserGuid() == $request->guid_two) {
            new Activity($request->guid_one, "activity:friends:new", $params2);
        } else {
            new Activity($request->guid_two, "activity:friends:new", $params1);
        }

        forward("friends");
    }

}

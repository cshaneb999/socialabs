<?php

/**
 * Class used to create and manipulate site activity objects
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

/**
 * Notification object
 */
class Notification extends Entity {

    public $message;
    public $link;

    /**
     * Constructor sets object type
     */
    public function __construct() {
        $this->type = "Notification";
        $this->access_id = "system";
    }

    /**
     * Returns url of notification
     * 
     * @return string   URL
     */
    public function getURL() {
        return $this->link;
    }

    /**
     * Returns a count of notifications for a given guid
     * 
     * @param int $guid Guid of user to get notifications for
     * @return int  Count of notifications
     */
    static function getNotificationCount($guid) {
        $access = getIgnoreAccess();
        setIgnoreAccess();
        $count = getEntities(array(
            "type" => "Notification",
            "count" => true,
            "metadata_name_value_pairs" => array(
                array(
                    "name" => "owner_guid",
                    "value" => getLoggedInUserGuid()
                )
            )
        ));
        setIgnoreAccess($access);
        return $count;
    }

    /**
     * Creates a notification object, and sends email to user
     * 
     * @param int $user_guid Guid of user to notify
     * @param string $message   Message to send to user
     * @param string $link  Link to object
     */
    static function notifyUser($user_guid, $message, $link) {
        $user = getEntity($user_guid);
        $notification = new Notification;
        $notification->message = $message;
        $notification->link = $link;
        $notification->owner_guid = $user_guid;
        $notification->save();
        sendEmail(array(
            "to" => array(
                "name" => $user->name,
                "email" => $user->email
            ),
            "from" => array(
                "name" => getSiteName(),
                "email" => getSiteEmail()
            ),
            "subject" => getSiteName() . " | You have received a notification.",
            "body" => $message . "<br/><a href='$link'>Click here to view it!</a>",
            "html" => true
        ));
    }

}

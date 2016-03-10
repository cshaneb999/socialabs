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

class Security {

    /**
     * Generates random token string
     * 
     * @return string
     */
    static function generateToken() {
        $token = Cache::get("token", false);
        if ($token) {
            return $token;
        } else {
            $time = floor(time() / 1000);
            $token = md5(bin2hex($time));
            new Cache("token", $token);
            return $token;
        }
    }

    /**
     * Determines of the current logged in user can view an entity
     * 
     * @param object $entity    Entity to test
     * @return boolean  true if user can view entity, false if user cannot view entity
     */
    static function loggedInUserCanViewEntity($entity, $ignore_access = false) {
        if ($ignore_access) {
            return true;
        }
        if (!is_object($entity)) {
            return true;
        }
        if ($entity->access_id == "system") {
            return true;
        }

        if (getIgnoreAccess()) {
            return true;
        }

        // Logged in user trying to view him/herself
        if (getLoggedInUserGuid() == $entity->guid) {
            return true;
        }

        // Logged in user owns entity
        if (getLoggedInUserGuid() == $entity->owner_guid) {
            return true;
        }

        // Admins can view everything
        if (adminLoggedIn()) {
            return true;
        }

        if (is_numeric($entity->access_id)) {
            $access_entity = getEntity($entity->access_id);
            if (is_a($access_entity, "SociaLabs\\Group")) {
                if (isEnabledPlugin("Groups")) {
                    if (GroupsPlugin::loggedInUserIsMember($access_entity)) {
                        return true;
                    }
                }
            }
            return false;
        }

        $access_handler = Accesshandler::get($entity->access_id);

        if ($access_handler) {
            $access_handler = ucfirst($access_handler) . "AccessHandler";
            $access_handler = "SociaLabs\\" . $access_handler;
            $return = (new $access_handler)->init($entity);
            return $return;
        }


        return false;
    }

    /**
     * Gatekeeper function for preventing CSRF attacks.  Post token must match session token for action top complete.
     * otherwise, site dies to the homepage.
     * 
     * Session variable is set via the view_form function.
     * 
     * @return boolean
     */
    static function tokenGatekeeper() {
        $token = getInput("token");
        $session_token = Cache::get("token", "session");
        if ($token == $session_token) {
            return true;
        }
        return false;
    }

    /**
     * Adds a token to a url
     * 
     * @param string $url   Url to add token to
     * @return string   url with token added
     */
    static function addTokenToURL($url) {
        $token = Security::generateToken();
        new Cache("token", $token);
        if (strpos($url, '?') !== false) {
            $url .= "&token=$token";
        } else {
            $url .= "?token=$token";
        }
        return $url;
    }

    /**
     * Gets an input (get, post, or cookie)
     *
     * @param string $name Name of input to get
     * @param string $value Default value if no input
     * @return mixed Output, or false if no output
     *
     */
    static function getInput($name, $value = false, $allow_get = true) {
        if (isset($_POST[$name])) {
            $output = $_POST[$name];
            if (!is_array($output)) {
                $output = htmlspecialchars($output);
            }
            return $output;
        }
        if ($allow_get) {
            if (isset($_GET[$name])) {
                $output = $_GET[$name];
                if (!is_array($output)) {
                    $output = Dbase::con()->real_escape_string($output);
                    $output = htmlspecialchars($output);
                }
                return $output;
            }
        }
        return $value;
    }

    /**
     * Gets site default access id
     * 
     * @return string Access id
     */
    static function getDefaultAccessId() {
        $access_id = Setting::get("default_access");
        if (!$access_id) {
            $access_id = "public";
        }
        return $access_id;
    }

    /**
     * Prevents non logged in users from viewing a page
     * 
     * @return boolean true if logged in, or forwards to home if not logged in
     */
    static function gateKeeper() {
        if (!loggedIn()) {
            new SystemMessage(translate("system_message:must_be_logged_in"));
            forward("home");
        }
        return true;
    }

    /**
     * Prevents logged in users from viewing a page
     * 
     * @return boolean true if logged out, or forwards to home if not logged out
     */
    static function reverseGatekeeper() {
        if (loggedIn()) {
            new SystemMessage(translate("system_message:must_be_logged_out"));
            forward("home");

            return false;
        }
        return true;
    }

    /**
     * Gatekeeper function that checks to make sure an entity is a member of a certain class, and
     * forwards to the index if the check fails
     * 
     * @param string $entity Entity to test
     * @param string $class Class to test against
     * @return boolean true
     */
    static function classGateKeeper($entity, $class) {
        $class = "SociaLabs\\" . $class;
        if (!is_a($entity, $class, true)) {
            new SystemMessage("Class Gatekeeper Exception", "danger");
            forward("home");
        }
        return true;
    }

    /**
     * Prevents non admins from viewing a page
     */
    static function adminGateKeeper() {
        if (!loggedIn() || !adminLoggedIn()) {
            new SystemMessage(translate("system_message:not_allowed_to_view"));
            forward("home");
        }
    }

    /**
     * Returns array of admin guids
     * 
     * @return array Array of admin guids
     */
    static function getAdminGuidArray() {
        $admin_array = array();
        $admins = getEntities(array(
            "type" => "User",
            "metadata_name" => "level", "metadata_value" => "admin"
        ));
        foreach ($admins as $admin) {
            $admin_array[] = $admin->
                    guid;
        }
        return $admin_array;
    }

    /**
     * Checks for empty fields on form submission
     * 
     * @param array $fieldnames Array of fieldnames that shouldn't be empty
     */
    static function checkForEmptyFields($fieldnames = array()) {

        $failure = false;
        foreach ($fieldnames as $fieldname) {
            $test = getInput($fieldname);
            if (!$test) {
                $failure = true;
                new SystemMessage($fieldname . " can't be left empty");
            }
        }
        if ($failure) {
            forward();
        }
    }

    /**
     * Checks if file field is empty on form submission
     * 
     * @param string $fieldname Fieldname that shouldn't be empty
     */
    static function checkForEmptyFileField($fieldname) {
        if (!isset($_FILES[$fieldname]['name'])) {
            new SystemMessage("File field cannot be left empty")

            ;
            forward();
        }
    }

}

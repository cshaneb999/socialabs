<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs;

/**
 * Class used to create user objects
 */
class User extends Entity {

    public $default_icon = "assets/img/avatars/default_user.svg";
    public $verified = "false";
    public $password_reset_code = NULL;
    public $profile_type = "default";
    public $session = NULL;
    public $access_id = "system";
    public $banned = false;

    /**
     * Constructor sets object type to User
     */
    public function __construct() {
        $this->type = "User";
        $this->url = $this->getURL();
        $this->avatar = $this->icon();
        parent::__construct();
    }

    /**
     * Returns the users url (profile)
     * 
     * @return string Url to user profile
     */
    public function getURL() {
        return getSiteURL() . "profile/" . $this->guid;
    }

    /**
     * Sends password reset email to user
     * 
     * @return boolean if email sent, false if not
     */
    public function sendPasswordResetLink() {
        $this->password_reset_code = $this->generateToken();
        $this->save();
        try {
            $mail = new \PHPMailer(true);
            $mail->From = getSiteEmail();
            $mail->FromName = getSiteName();
            $mail->addAddress($this->email);
            $mail->isHTML(true);
            $mail->Subject = display("email/forgot_password_subject");
            $mail->Body = display("email/forgot_password_body", array(
                "user_guid" => $this->guid
            ));
            $mail->From = getSiteEmail();
            $mail->FromName = getSiteName();

            $mail->isHTML(true); // Set email format to HTML

            $mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Saves user entity to database
     * 
     * @return int  Guid of user
     */
    public function save() {
        $this->full_name = $this->first_name . " " . $this->last_name;
        return parent::save();
    }

    /**
     * Checks if user is online
     * 
     * @return boolean  true if loggedin, false if not logged in
     */
    public function online() {
        if (isset($this->session) && $this->session) {
            return true;
        }
        return false;
    }

    /**
     * Login user
     */
    public function logIn() {
        if ($this->verified != "true") {
            forward("verificationEmailSent/" . $this->guid);
        }
        $hash = substr(bin2hex(\mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)), 0, 50);
        $this->session = $hash;
        $this->online = "true";
        $this->save();

        setcookie(SITESECRET . "_guid", $this->guid, time() + (86400), "/");
        setcookie(SITESECRET . "_session", $hash, time() + (86400), "/");
        $_POST['guid'] = $this->guid;
        $_POST['session'] = $hash;
        return;
    }

    /**
     * Logout user
     */
    public function logOut() {
        $this->session = NULL;
        $this->online = "false";
        $this->save();
        setcookie(SITESECRET . "_guid", NULL, time() - (86400 * 1), "/"); // 86400 = 1 day
        setcookie(SITESECRET . "_session", NULL, time() - (86400 * 1), "/"); // 86400 = 1 day
        return;
    }

    /**
     * Checks if user is logged in
     * 
     * @return boolean|int false if not logged , user guid if logged in
     */
    static function loggedIn() {
        // cookies aren't available until page refresh.  This is a workaround for the action:login:after hook to be able to 
        // access the logged in user functions
        if (isset($_POST['guid']) && isset($_POST['session'])) {
            $guid = sanitize($_POST['guid']);
            $session = sanitize($_POST['session']);
        } elseif (isset($_COOKIE[SITESECRET . "_guid"]) && isset($_COOKIE[SITESECRET . "_session"])) {
            $guid = $_COOKIE[SITESECRET . "_guid"];
            $session = $_COOKIE[SITESECRET . "_session"];
        }
        if (isset($guid) && isset($session)) {
            $user = getEntity(array(
                "type" => "User",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "guid",
                        "value" => $guid
                    ),
                    array(
                        "name" => "session",
                        "value" => $session
                    )
                )
            ));
            if ($user) {
                $guid = $user->guid;
                return $guid;
            }
        }
        return false;
    }

    /**
     * Returns true if user logged out
     * @return boolean  true if logged out, false if logged in
     */
    static function loggedOut() {
        return !loggedIn();
    }

    /**
     * Returns the logged in user object
     * 
     * @return boolean false if not logged in|object logged in user object
     */
    static function getLoggedInUser() {
        global $logged_in_user;
        if (isset($logged_in_user)) {
            return $logged_in_user;
        }
        $user_guid = loggedIn();
        if ($user_guid) {
            $logged_in_user = getEntity($user_guid, true);
            return $logged_in_user;
        }
        return false;
    }

    /**
     * Returns logged in user guid
     * 
     * @return int|boolean  guid of logged in user, or false if no user logged in
     */
    static function getLoggedInUserGuid() {
        return loggedIn();
    }

    /**
     * Checks if the site administrator is logged in
     * 
     * @return boolean false if admin not logged in|boolean true if admin is logged in
     */
    static function adminLoggedIn() {
        $user = getLoggedInUser();

        if (!$user) {
            return false;
        }
        if ($user->level == "admin") {
            return true;
        }
        return false;
    }

    /**
     * Returns array of online users
     * @return array  Array of online users
     */
    static function getOnlineUsers() {
        $users = getEntities(array(
            "type" => "User",
            "metadata_name" => "session",
            "metadata_value" => "NULL",
            "operand" => "IS NOT"
        ));
        return $users;
    }

    public function getSetting($name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
        $settings = Cache::get("user_settings", "session");
        if ($settings) {
            foreach ($settings as $key => $setting) {
                if ($key == $name) {
                    return $setting['default_value'];
                }
            }
        }
        return NULL;
    }

}

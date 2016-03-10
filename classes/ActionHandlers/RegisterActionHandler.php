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

/**
 * Register action handler
 */
class RegisterActionHandler {

    /**
     * Registers a user
     */
    public function __construct() {
        $registration_fields = getAllRegistrationFields();
        runHook("action:register:before");
        foreach ($registration_fields as $field) {
            $name = $field->name;
            $$name = getInput($name);
        }

        // Check if email banned by admin
        $banned = getEntity(array(
            "type" => "BlacklistEmail",
            "metadata_name" => "email",
            "metadata_value" => $email
        ));
        if ($banned) {
            new SystemMessage("Your email address has been banned.");
            forward("home");
        }
        $ip = NULL != getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : "";

        if ($ip) {
            $banned = getEntity(array(
                "type" => "BlacklistIp",
                "metadata_name" => "ip",
                "metadata_value" => $ip
            ));
            if ($banned) {
                new SystemMessage("Your ip has been banned.");
                forward("home");
            }
        }

        $ip2 = NULL != getenv('HTTP_X_FORWARDED_FOR') ? getenv('HTTP_X_FORWARDED_FOR') : "";
        if ($ip2) {
            $banned = getEntity(array(
                "type" => "BannedIp",
                "metadata_name" => "ip",
                "metadata_value" => $ip2
            ));
            if ($banned) {
                new SystemMessage("Your ip has been banned.");
                forward("home");
            }
        }
        $banned_emails = json_decode(file_get_contents(getSitePath() . "data/banned_email_providers.json"));
        foreach ($banned_emails as $banned_email) {
            if ((strpos($email, $banned_email)) !== false) {
                new SystemMessage("Sorry, temporary email addresses aren't allowed.");
                forward("register?first_name=" . $first_name . "&last_name=" . $last_name . "&email=" . $email . "&message_type=danger");
            }
        }

        if (isset($password) && isset($password2) && isset($email)) {
            if ($password != $password2) {
                new SystemMessage(translate("system_message:passwords_must_match"));
                forward("register?first_name=" . $first_name . "&last_name=" . $last_name . "&email=" . $email . "&message_type=danger");
            }
            $access = getIgnoreAccess();
            setIgnoreAccess();
            $test = getEntities(array(
                "type" => "User",
                "metadata_name" => "email",
                "metadata_value" => $email,
                "limit" => 1
            ));
            setIgnoreAccess($access);
            if ($test) {
                new SystemMessage(translate("system_message:email_taken"));
                forward("register?first_name=" . $first_name . "&last_name=" . $last_name . "&email=" . $email . "&message_type=danger");
            }

            $user = new User;
            foreach ($registration_fields as $field) {
                if (isset($field->name)) {
                    $name = $field->name;
                    $user->$name = $$name;
                }
            }
            $user->password = password_hash($password, PASSWORD_BCRYPT);
            $user->verified = "false";
            unset($user->password2);
            $user_exists = getEntities(array(
                "type" => "User",
                "limit" => 1
            ));
            if (!$user_exists) {
                $user->level = "admin";
                $user->verified = "true";
                new SystemMessage("Since you are the first registered user, your account has been setup as the site administrator, and your email verified.");
            }

            $ip1 = NULL != getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : "";
            $ip2 = NULL != getenv('HTTP_X_FORWARDED_FOR') ? getenv('HTTP_X_FORWARDED_FOR') : "";

            $user->ip1 = $ip1;
            $user->ip2 = $ip2;
            $user->save();

            runHook("send_verification_email:before");
            $email_sent = Email::sendVerificationEmail($user);
            runHook("send_verification_email:after");
            runHook("action:register:after", array('user' => $user));
            if ($email_sent) {
                forward("VerificationEmailSent/" . $user->guid);
            } else {
                forward("home");
            }
        }
    }

}

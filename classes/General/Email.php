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

class Email {

    public function __construct($params) {
        self::sendEmail($params);
    }

    /**
     * Sends email to user
     * 
     * @param array $params Email Paramaters.  Includes:
     *                          "to"=>array(
     *                              "name"=>$name,
     *                              "email"=>$email
     *                              ),
     *                          "from"=>array(
     *                              "name"=>$name,
     *                              "email"=>$email
     *                              ),
     *                          "subject",
     *                          "body",
     *                          "html"
     */
    static function sendEmail($params) {
        if (!isset($params['html'])) {
            $params['html'] = true;
        }
        try {
            $mail = new \PHPMailer(true);
            $mail->From = $params['from']['email'];
            $mail->FromName = $params['from']['name'];
            $mail->addAddress($params['to']['email'], $params['to']['name']);
            $mail->Subject = $params['subject'];
            $mail->Body = $params['body'];
            $mail->isHTML($params['html']);
            $mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sends a verification email to a site user
     * 
     * @param object $user User to send email to
     * @return boolean true
     */
    static function sendVerificationEmail($user) {
        if ($user->verified == "true") {
            return false;
        }
        $user->email_verification_code = randString(70);
        $user->save();
        if (sendEmail(array(
                    "from" => array(
                        "email" => getSiteEmail(),
                        "name" => getSiteName()
                    ),
                    "to" => array(
                        "email" => $user->email,
                        "name" => $user->first_name . " " . $user->last_name
                    ), "subject" => display("email/verify_email_subject", array(
                        "user_guid" => $user->guid
                    )), "body" => display("email/verify_email_body", array(
                        "user_guid" => $user->guid
                    ))
                ))) {
            return true;
        }
        $user->email_verification_code = NULL;
        $user->save();
        return false;
    }

}

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
 * Forgot password page handler
 */
class ForgotPasswordPageHandler extends PageHandler {

    /**
     * Creates html for forgot password page
     */
    public function __construct() {
    $code = pageArray(1);
    $email = pageArray(2);

    if ($code && $email) {
        $access = getIgnoreAccess();
        setIgnoreAccess();
        $user = getEntities(array(
                    "type" => "User",
                    "metadata_name_value_pairs" => array(
                        array(
                            "name" => "email",
                            "value" => $email
                        ),
                        array(
                            "name" => "password_reset_code",
                            "value" => $code
                        )
                    )
        ));
        setIgnoreAccess($access);
        if ($user) {
        $user = $user[0];
        new Vars("guid", $user->guid);
        new Vars("code", $code);
        $form = drawForm(array(
                    "name" => "new_password",
                    "method" => "post",
                    "action" => "newPassword"
        ));
        $header = "Enter your new password.";
        $this->html = drawPage($header, $form);
        $this->html = drawPage(array(
                    "header" => $header,
                    "body" => $form
        ));
        }
    } else {
        $form = drawForm(array(
                    "name" => "forgot_password",
                    "method" => "post",
                    "action" => "ForgotPassword"
        ));
        $this->html = drawPage(array(
                    "header" => "Reset Your Password",
                    "body" => $form
        ));
    }
    }

}

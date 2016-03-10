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
 * Verify email action handler
 * 
 * Access handler that verifies a new user's email
 */
class VerifyEmailActionHandler {

  /**
   * Verifies a new user's email
   * 
   * @return false|null
   */
  public function __construct() {
    if (!pageArray(2) || !pageArray(3)) {
      return false;
    }
    $email = pageArray(2);
    $code = pageArray(3);

    runHook("action:verify_email:before");

    $access = getIgnoreAccess();
    setIgnoreAccess();
    $user = getEntities(
                    array(
                        "type" => "User",
                        "metadata_name_value_pairs" => array(
                            array(
                                "name" => "email",
                                "value" => $email
                            ),
                            array(
                                "name" => "email_verification_code",
                                "value" => $code
                            )
                        )
                    )
    );

    setIgnoreAccess($access);
    if (!$user) {
        new SystemMessage(translate("system_message:email_could_not_be_verified"));
        forward("home");
    }
    $user = $user[0];
    $user->email_verification_code = NULL;
    $user->verified = "true";
    $user->save();

    runHook("action:verify_email:after");

    new SystemMessage(translate("system_message:email_verified"));
    new Activity($user->guid, "activity:joined", array(
        $user->getURL(),
        $user->full_name
    ));
    forward("login");
    }

}

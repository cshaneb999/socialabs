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

class EditProfileActionHandler {

    public function __construct() {
        gateKeeper();
        $message = NULL;
        $user = getLoggedInUser();
        $user->profile_complete = true;
        $profile_type = $user->profile_type;
        $fields = ProfileField::get($profile_type);


        foreach ($fields as $key => $field) {
            if ($field['required'] == "true" && !getInput($key)) {
                $message .= "{$field['label']} cannot be empty.";
            }
        }

// If field is empty, forward to edit profile page with error
        if ($message) {
            new SystemMessage($message);
            forward("editProfile");
        }

// Set user fields
        foreach ($fields as $key => $field) {
            $user->$key = getInput($key);
        }

// Save user
        $user->save();

        new Activity($user->guid, "activity:profile:updated", array(
            $user->getURL(),
            $user->full_name
        ));

// Forward to profile page
        new SystemMessage("Your profile has been updated.");
        forward("profile/$user->guid");
    }

}

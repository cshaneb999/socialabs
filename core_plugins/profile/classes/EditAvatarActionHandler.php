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

class EditAvatarActionHandler {

    public function __construct() {
        gateKeeper();
        $user = getLoggedInUser();

        $user->createAvatar();

        if (isEnabledPlugin("photos")) {
            $album = getEntity(array(
                "type" => "Photoalbum",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "owner_guid",
                        "value" => getLoggedInUserGuid()
                    ),
                    array(
                        "name" => "title",
                        "value" => "Profile Avatars"
                    )
                )
            ));


            $photo = new Photo;
            $photo->owner_guid = getLoggedInUserGuid();
            $photo_guid = $photo->save();
            Image::copyAvatar($user, $photo);
            $photo = getEntity($photo_guid);
            if (!$album) {
                $album = new Photoalbum;
                $album->owner_guid = getLoggedInUserGuid();
                $album->title = "Profile Avatars";
                $album_guid = $album->save();
                $album = getEntity($album_guid);
                Image::copyAvatar($photo, $album);
            }
            $photo->container_guid = $album->guid;
            $photo->save();
        }

        runHook("action:edit_avatar:after", array(
            "user" => $user
        ));

        new Activity(getLoggedInUserGuid(), "activity:avatar:updated", array(
            $user->getURL(),
            $user->full_name
        ));

// Return to profile
        new SystemMessage("Your avatar has been uploaded.");
        forward("profile/" . $user->guid);
    }

}

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

class UploadPhotoActionHandler {

    public function __construct() {
        $editor = getInput("editor_id");
        if (file_exists($_FILES['avatar']['tmp_name'])) {
            // Check if General album exists
            $album = getEntity(array(
                "type" => "Photoalbum",
                "metadata_name_value_pairs" => array(
                    array(
                        "name" => "owner_guid",
                        "value" => getLoggedInUserGuid()
                    ),
                    array(
                        "name" => "title",
                        "value" => "General"
                    )
                )
            ));
            $photo = new Photo;
            $photo->owner_guid = getLoggedInUserGuid();
            $photo->save();
            $photo->createAvatar();

            if (!$album) {
                $album = new Photoalbum;
                $album->title = "General";
                $album->owner_guid = getLoggedInUserGuid();
                $album->access_id = "public";
                Image::copyAvatar($photo, $album);
                $album->save();
            }

            $photo->container_guid = $album->guid;
            if (!$album->title != "Profile Avatars" && $album->title != "General") {
                new Activity(getLoggedInUserGuid(), "activity:add:photo", array(
                    getLoggedInUser()->getURL(),
                    getLoggedInUser()->full_name,
                    $album->getURL(),
                    $album->title,
                    "<a href='" . $album->getURL() . "'>" . $photo->icon(EXTRALARGE, "img-responsive") . "</a>"
                        ), $album->access_id);
            }
            $photo->save();
            forward(false, array(
                "insertphoto" => $photo->guid,
                "editor" => $editor
            ));
        } else {
            forward();
        }
    }

}

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

class UploadVideoActionHandler {

    public function __construct() {
        $editor = getInput("editor_id");
        // Check if General album exists
        $album = getEntity(array(
            "type" => "Videoalbum",
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
        $logged_in_user = getLoggedInUser();
        $logged_in_user_guid = $logged_in_user->guid;
        $title = getInput("title");
        $description = getInput("description");

        if (!file_exists($_FILES['video_file']['tmp_name']) || !is_uploaded_file($_FILES['video_file']['tmp_name'])) {
            $video_type = "youtube";
        } else {
            $video_type = "upload";
        }

        $video = new Video;
        $video->video_type = $video_type;
        $video->title = $title;
        $video->description = $description;
        $video->owner_guid = getLoggedInUserGuid();
        $video->access_id = getInput("access_id");
        $video->save();
        if ($video_type == "youtube") {
            $video->url = getInput("url");
        } else {
            $guid = VideosPlugin::processUploadedVideo("video_file", $video);
            $video->video_guid = $guid;
            $video->save();
            $video->createAvatar();
        }
        $video->save();
        new Activity(getLoggedInUserGuid(), "activity:video:add", array(
            getLoggedInUser()->getURL(),
            getLoggedInUser()->full_name,
            $video->getURL(),
            $video->title,
            $video->getURL(),
            $video->icon(LARGE, "img-responsive")
        ));
        new SystemMessage("Your video has been uploaded.");

        if (!$album) {
            $album = new Videoalbum;
            $album->title = "General";
            $album->owner_guid = getLoggedInUserGuid();
            $album->save();
        }

        $video->container_guid = $album->guid;
        $video->save();
        forward(false, array(
            "insertvideo" => $video->guid,
            "editor" => $editor
        ));
    }

}

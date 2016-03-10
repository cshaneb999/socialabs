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

class AddPhotoActionHandler {

    public function __construct() {
        $container_guid = getInput("container_guid");
        $title = getInput("title");
        $description = getInput("description");
        $access_id = getInput("access_id");
        for ($i = 0; $i < count($_FILES['avatar']['name']); $i++) {
            $photo = new Photo;
            $photo->container_guid = $container_guid;
            $photo->owner_guid = getLoggedInUserGuid();
            $photo->title = $title;
            $photo->description = $description;
            $photo->save();
            $file = new File;
            $file->container_guid = $photo->guid;
            $file->owner_guid = getLoggedInUserGuid();
            $file->access_id = $photo->access_id;
            $file_guid = $file->save();
            $file_entity = getEntity($file_guid);
            $target_dir = getDataPath() . "files" . "/" . $file_guid . "/";
            if (!file_exists($target_dir)) {
                makePath($target_dir, 0777, true);
            } else {
                $files = glob($target_dir . '*', GLOB_MARK);
                foreach ($files as $file) {
                    @unlink($file);
                }
            }

            $name = basename($_FILES['avatar']["name"][$i]);
            $name = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $name);
            $name = preg_replace("([\.]{2,})", '', $name);
            $target_file = $target_dir . $name;
            $file_entity->path = $target_file;
            $file_entity->extension = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_entity->save();
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            if (!empty($allowed_extensions)) {
                if (!in_array(strtolower($imageFileType), $allowed_extensions)) {
                    $file_entity->delete();
                    new SystemMessage("Allowed file types: " . implode(" ", $allowed_extensions));
                    forward();
                }
            }
            $error = move_uploaded_file($_FILES['avatar']["tmp_name"][$i], $target_file);
            $finfo = \finfo_open(FILEINFO_MIME_TYPE);
            $mime = \finfo_file($finfo, $target_file);
            \finfo_close($finfo);
            if ($mime == "image/jpeg" || $mime == "image/jpg" || $mime == "image/gif") {
                Image::fixImageRotation($target_file);
            }
            $file_entity->file_location = $target_file;
            $file_entity->mime_type = $mime;
            $file_entity->filename = $name;
            $file_entity->save();
            $filename = $name;
            $file = getEntity($file_guid);
            $file->filename = $filename;
            $file->save();
            $photo->icon = $file->guid;
            $photo->save();
            Image::createThumbnail($file->guid, TINY);
            Image::createThumbnail($file->guid, SMALL);
            Image::createThumbnail($file->guid, MEDIUM);
            Image::createThumbnail($file->guid, LARGE);
            Image::createThumbnail($file->guid, EXTRALARGE);
            Image::createThumbnail($file->guid, HUGE);
        }


        new SystemMessage("Your Photo has been Uploaded");
        $album_guid = $container_guid;
        $album = getEntity($album_guid);
        if (!$album->title != "Profile Avatars" && $album->title != "General") {
            new Activity(getLoggedInUserGuid(), "activity:add:photo", array(
                getLoggedInUser()->getURL(),
                getLoggedInUser()->full_name,
                $album->getURL(),
                $album->title,
                "<a href='" . $album->getURL() . "'>" . $photo->icon(MEDIUM, "img-responsive") . "</a>"
            ));
        }
        forward($album->getURL());
    }

}

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

class FileUploadActionHandler {

    public function __construct() {
        $title = getInput("title");
        $description = getInput("description");
        // Create filestore object to store file information
        $file = new File;
        $file->title = $title;
        $file->description = $description;
        $file->owner_guid = getLoggedInUserGuid();
        $file->access_id = "public";
        $file->container_guid = getInput("container_guid");
        $guid = $file->save();

        uploadFile("file", $guid, getLoggedInUserGuid());
        $file = getEntity($guid);
        Image::createThumbnail($file->guid, TINY);
        Image::createThumbnail($file->guid, SMALL);
        Image::createThumbnail($file->guid, MEDIUM);
        Image::createThumbnail($file->guid, LARGE);
        Image::createThumbnail($file->guid, EXTRALARGE);
        Image::createThumbnail($file->guid, HUGE);

        new Activity(getLoggedInUserGuid(), "action:upload:file", $guid);

        runHook("upload_file:redirect");

        new SystemMessage("Your file has been uploaded.");
        forward();
    }

}

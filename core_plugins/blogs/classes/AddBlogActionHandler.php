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

class AddBlogActionHandler {

    public function __construct() {
        gateKeeper();
        $guid = getInput("guid");
        $title = getInput("blog_title");
        $description = getInput("description");
        $access_id = getInput("access_id");
        $container_guid = getInput("container_guid");
        $owner_guid = getLoggedInUserGuid();

        if ($guid) {
            $blog = getEntity($guid);
        } else {
            $blog = new Blog;
        }

        $blog->title = $title;
        $blog->description = $description;
        $blog->access_id = $access_id;
        $blog->owner_guid = $owner_guid;
        $blog->status = "published";
        $blog->container_guid = $container_guid;
        $blog->save();
        new Activity(getLoggedInUserGuid(), "blog:add", array(
            getLoggedInUser()->getURL(),
            getLoggedInUser()->full_name,
            $blog->getURL(),
            $blog->title,
            truncate($blog->description)
                ), "", $access_id);
        new SystemMessage("Your blog has been published");
        forward("blogs/all_blogs");
    }

}
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

class BlogsPageHandler extends PageHandler {

    public function __construct() {
        $title = $buttons = $body = NULL;
        if (BlogsPlugin::userCanCreateBlog()) {
            $body = display("page_elements/blogs_tabs");
            $buttons = "<a href='" . getSiteURL() . "blogs/add' class='btn btn-success'>Add a Blog</a>";
        }
        switch (pageArray(1)) {
            case "all_blogs":
            default:
                $title = "Blogs";
                $body .= display("pages/all_blogs");
                break;
            case "friends_blogs":
                $title = translate("friends_blogs");
                $body .= display("pages/friends_blogs");
                break;
            case "my_blogs":
                $title = "My Blogs";
                $body .= display("pages/my_blogs");
                break;
            case "add":
                if (BlogsPlugin::userCanCreateBlog()) {
                    $title = "Add a Blog";
                    $body = drawForm(array(
                        "name" => "add_blog",
                        "method" => "post",
                        "action" => "addBlog"
                    ));
                }
                break;
            case "view":
                $guid = pageArray(2);
                $blog = getEntity($guid);
                if ($blog) {
                    $title = $blog->title;
                }
                $owner = getEntity($blog->owner_guid);
                $title .= " <small>by $owner->full_name</small>";
                $body = display("pages/blog");
                if (getLoggedInUserGuid() == $blog->owner_guid) {
                    $edit_url = getSiteURL() . "blogs/edit/$guid";
                    $delete_url = addTokenToURL(getSiteURL() . "action/deleteBlog/$guid");
                    $buttons = "<a href='$edit_url' class='btn btn-warning'>Edit</a>";
                    $buttons .= "<a href='$delete_url' class='btn btn-danger confirm'>Delete</a>";
                }
                break;
            case "edit":
                $buttons = NULL;
                $title = "Edit your blog";
                $body = drawForm(array(
                    "name" => "edit_blog",
                    "method" => "post",
                    "action" => "addBlog"
                ));
                break;
        }
        $this->html = drawPage(array(
            "header" => $title,
            "body" => $body,
            "button" => $buttons
        ));
    }

}

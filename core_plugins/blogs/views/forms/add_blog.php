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

$container_guid = pageArray(2);
if ($container_guid) {
    echo display("input/hidden", array(
        "name" => "container_guid",
        "value" => $container_guid
    ));
}
echo "<div style='display:none;'>";
echo display("input/text", array(
    "name" => "guid",
    "value" => NULL
));
echo "</div>";
echo display("input/text", array(
    "name" => "blog_title",
    "class" => "form-control",
    "value" => NULL,
    "label" => "Blog Title"
));
echo display("input/editor", array(
    "name" => "description",
    "class" => "form-control",
    "value" => NULL,
    "label" => "Blog Body"
));
echo display("input/access", array(
    "label" => "Privacy",
    "name" => "access_id",
    "value" => "public"
));
echo display("input/submit", array(
    "class" => "btn btn-success",
    "label" => "Publish"
));
echo "<span class='label label-info last_updated' style='margin-left:14px;display:none;'>Draft saved <span class='timeago'></span></span>";

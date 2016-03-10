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

$guid = Vars::get("guid");
$reverse = Vars::get("reverse");
if (!$reverse) {
    $reverse = false;
}
if ($guid && loggedIn()) {
    $form = "<button class='btn btn-info btn-xs show_inline_comment_form'>Add Comment</button>";
    $form .= drawForm(array(
                "name" => "add_inline_comment",
                "method" => "post",
                "action" => "addComment",
                "class" => "inline_comment_form",
                "inputs" => array(
                    "guid" => $guid
                )
    ));

    echo $form;
}
$comments = <<<HTML
        
<div class='bar-loader'></div>
<ul class="commentList comment_list_$guid">
HTML;
$comments .= display("output/comments", array(
            "guid" => $guid,
            "count" => 4,
            "reverse" => true,
            "offset" => 0
        ));
$comments .= <<<HTML
</ul>
HTML;
echo $comments;
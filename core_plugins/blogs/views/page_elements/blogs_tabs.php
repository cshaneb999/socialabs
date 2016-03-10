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

$page = pageArray(1);
if (!$page) {
    $page = "all_blogs";
}
$all_blogs = getSiteURL(). "blogs/all_blogs";
$my_blogs = getSiteURL(). "blogs/my_blogs";
$friends_blogs = getSiteURL(). "blogs/friends_blogs";
?>
<div class='container' style='margin-bottom:10px;'>
    <div class='row'>
        <ul class="nav nav-pills">
            <li role="presentation" class="<?php echo $page == "all_blogs" ? "active" : ""; ?>"><a href="<?php echo $all_blogs; ?>">All Blogs</a></li>
            <li role="presentation" class="<?php echo $page == "my_blogs" ? "active" : ""; ?>"><a href="<?php echo $my_blogs; ?>">My Blogs</a></li>
            <li role="presentation" class="<?php echo $page == "friends_blogs" ? "active" : ""; ?>"><a href="<?php echo $friends_blogs; ?>"><?php echo translate("friends_blogs"); ?></a></li>
        </ul>
    </div>
</div>
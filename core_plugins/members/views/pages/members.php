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
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

$offset = getInput("offset", 0);
$params = array(
    "type" => "User",
    "order_by" => "time_created",
    "order_reverse" => true,
    "limit" => 5,
    "offset" => $offset
);

$first_member = getEntity($params);
if ($first_member) {
    $guid = $first_member->guid;
}

$members = listEntities($params);

unset($params['limit']);
unset($params['offset']);
$params['count'] = true;
$count = getEntities($params);
?>
<div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
        <?php echo $members; ?>
    </div>
    <div class="col-md-9 hidden-xs hidden-sm" id="member_wrapper">
        <?php
        echo display("profile/mini_profile", array(
            "guid" => $guid
        ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        echo display("page_elements/pagination", array(
            "count" => $count,
            "offset" => $offset,
            "limit" => 5,
            "url" => getSiteURL() . "members"
        ));
        ?>
    </div>
</div>
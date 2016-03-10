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

$guid = pageArray(1);
$user = getEntity($guid);
$guids = array($user->guid);
$friends = FriendsPlugin::getFriends($user);
$entities = array();
$body = NULL;
if ($friends && !empty($friends)) {
    foreach ($friends as $friend) {
        $guids[] = $friend->guid;
    }
}
if ($guids) {
    $guids = join(',', $guids);
    $results = getEntities(array(
        "type" => "Activity",
        "limit" => 10,
        "metadata_name" => "owner_guid",
        "metadata_value" => "(" . $guids . ")",
        "operand" => "IN",
        "order_by" => "time_created",
        "order_reverse" => true
    ));
}
$body = viewEntityList($results);
echo <<<HTML
<div class='panel panel-default'>
    <div class='panel-heading'>
        Activity
    </div>
    <div class='panel-body'>
        <ul class='timeline timeline-small'>
            $body
        </ul>
    </div>
</div>
HTML;

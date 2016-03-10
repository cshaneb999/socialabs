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

$guid = Vars("guid");
if ($guid) {
    $message = getEntity($guid);
    if (!is_a($message, "SociaLabs\Message")) {
        die();
    }
    if (($message->to != getLoggedInUserGuid()) && ($message->from != getLoggedInUserGuid())) {
        die();
    }
    $to = $message->to;
    $from = $message->from;
    $to_user = getEntity($to);
    $from_user = getEntity($from);
    $icon1 = $to_user->icon(SMALL);
    $icon2 = $from_user->icon(SMALL);
    $to_user_url = $to_user->getURL();
    $from_user_url = $from_user->getURL();
    $icons = "<span class='pull-right' style='height:32px;width:32px;background:#F5F8FA;margin-left:4px;border-radius:8px;'><a href='$to_user_url'>$icon1</a></span>";
    $icons .= "<span class='pull-right' style='height:32px;width:32px;background:#F5F8FA;margin-left:4px;border-radius:8px;'><a href='$from_user_url'>$icon2</a></span>";
    $delete_url = addTokenToURL(getSiteURL() . "action/deleteMessage/" . $message->guid);
    $buttons = "<a class='pull-right btn btn-danger btn-xs confirm' href='$delete_url' style='margin-left:8px;'>Delete Messages</a>";
    $message_entities = listEntities(array(
        "type" => "Messageelement",
        "metadata_name" => "container_guid",
        "metadata_value" => $guid
    ));
    ?>
    <div class="panel">
        <div class="panel-heading">
            <?php
            echo $buttons;
            echo $icons;
            echo $message->subject;
            ?>
        </div>
        <div class="panel-body">
            <?php echo $message_entities; ?>
        </div>
    </div>
    <?php
    echo drawForm(array(
        "name" => "message_reply",
        "method" => "post",
        "action" => "MessageReply",
    ));
}
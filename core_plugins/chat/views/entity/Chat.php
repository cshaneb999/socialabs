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
$chat = getEntity($guid);
$user_one_guid = $chat->user_one == getLoggedInUserGuid() ? $chat->user_one : $chat->user_two;
$user_two_guid = $chat->user_two == getLoggedInUserGuid() ? $chat->user_one : $chat->user_two;

$user_one = getEntity($user_one_guid);
$user_two = getEntity($user_two_guid);

$icon = $user_two->icon(MEDIUM, "media-object");
$url = $user_two->getURL();

$sitename = getSiteName();
$name = $user_two->full_name;

$messages = display("chat/messages", array(
    "guid" => $guid
        ));

if ($chat->user_one == getLoggedInUserGuid()) {
    $closed = $chat->user_one_closed;
    $minimized = $chat->user_one_maximized ? "" : "style='display:none;'";
    $icon = $chat->user_one_maximized ? "glyphicon glyphicon-minus" : "glyphicon glyphicon-plus";
} else {
    $closed = $chat->user_two_closed;
    $minimized = $chat->user_two_maximized ? "" : "style='display:none;'";
    $icon = $chat->user_two_maximized ? "glyphicon glyphicon-minus" : "glyphicon glyphicon-plus";
}
$delete_url = addTokenToURL(getSiteURL() . "action/DeleteChatMessages/" . $guid);
if (!$closed) {
    ?>
    <div class="chat-window" id="chat_window_<?php echo $guid; ?>">
        <div class="container-fluid">
            <div class="panel panel-default" style="margin-bottom:0px;">
                <div class="panel-heading top-bar clearfix">
                    <div class="col-md-7 col-xs-7">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span>&nbsp;<?php echo $user_two->full_name; ?></h3>
                    </div>
                    <div class="col-md-5 col-xs-5" style="text-align: right;">
                        <span class="dropdown">
                            <a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer;">
                                <i class="glyphicon glyphicon-th-list"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel" style="z-index:100000;right:-16px;">
                                <li><a href="<?php echo $delete_url; ?>" class="confirm">Delete Conversation</a></li>
                            </ul>
                        </span>
                        <a href="#"><span id="minim_chat_window_<?php echo $guid; ?>" class="<?php echo $icon; ?> icon_minim"></span></a>
                        <a href="#"><span class="glyphicon glyphicon-remove icon_close" data-id="chat_window_<?php echo $guid; ?>"></span></a>
                    </div>
                </div>
                <div class="panel-body msg_container_base" id="messages_<?php echo $guid; ?>" <?php echo $minimized; ?>>
                    <?php echo $messages; ?>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input_<?php echo $guid; ?>" type="text" class="form-control input-sm chat_input" placeholder="Write your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm btn-send-chat" id="btn-chat_<?php echo $guid; ?>">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
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
$message_entity = getEntity($guid);
$from_guid = $message_entity->from;
$from_user = getEntity($from_guid);
$icon = $from_user->icon(MEDIUM, "media-object");
$message_body = display("output/editor", array(
    "value" => $message_entity->message
        ));
$url = $from_user->getURL();
$timeago = display("output/friendly_time", array(
    "timestamp" => $message_entity->time_created
        ));
$read = $message_entity->read;
if (($read == "false") && ($message_entity->to == getLoggedInUserGuid())) {
    $class = "unread";
} else {
    $class = "";
}
if (getLoggedInUserGuid() == $message_entity->to) {
    $message_entity->read = "true";
    $message_entity->save();
} 
?>
<hr>
<div class="media <?php echo $class; ?>">
    <div class="media-left">
        <a href="<?php echo $url; ?>">
            <?php echo $icon; ?>
        </a>
    </div>
    <div class="media-body">
        <strong>
            <a href="<?php echo $url; ?>">    
                <?php echo $from_user->full_name; ?>
                <?php echo $timeago; ?>
            </a>
        </strong><br/>
        <?php echo $message_body; ?>
    </div>
</div>
<hr>
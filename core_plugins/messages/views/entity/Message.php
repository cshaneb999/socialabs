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
$class = NULL;
$message = getEntity($guid);
$from_user_guid = $message->from;
$from_user = getEntity($from_user_guid);
$icon = $from_user->icon(MEDIUM, "media-object");
$from_user_url = $from_user->getURL();
$subject = truncate($message->subject, 20);
$timeago = display("output/friendly_time", array(
    "timestamp" => $message->time_created
        ));
if (pageArray(1) == $guid) {
    $class = "selected";
}
?>
<button class="list-group-item message_list_element <?php echo $class; ?>" data-guid="<?php echo $guid; ?>">
    <span class="media">
        <span class="media-left">
            <?php echo $icon; ?>
        </span>
        <span class="media-body">
            <strong><?php echo $from_user->full_name; ?></strong><br/>
            <?php echo $subject; ?><br/>
            <?php echo $timeago; ?>
        </span>
    </span>
</button>
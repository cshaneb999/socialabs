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

if (!$guid) {
    $first_message = getEntity(array(
        "type" => "Message",
        "metadata_name_value_pairs" => array(
            array(
                "name" => "to",
                "value" => getLoggedInUserGuid()
            ),
            array(
                "name" => "from",
                "value" => getLoggedInUserGuid()
            )
        ),
        "metadata_name_value_pairs_operand" => "OR",
        "order_by" => "time_created",
        "order_reverse" => true
    ));
    if ($first_message) {
        $guid = $first_message->guid;
    }
}

$messages = listEntities(array(
    "type" => "Message",
    "metadata_name_value_pairs" => array(
        array(
            "name" => "to",
            "value" => getLoggedInUserGuid()
        ),
        array(
            "name" => "from",
            "value" => getLoggedInUserGuid()
        )
    ),
    "metadata_name_value_pairs_operand" => "OR",
    "order_by" => "time_created",
    "order_reverse" => true
        ));
?>
<div class="modal fade" id="new_message_modal" tabindex="-1" role="dialog" aria-labelledby="new_message_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="new_message_modal_label">New Message</h4>
            </div>
            <div class="modal-body clearfix">
                <?php
                echo drawForm(array(
                    "name" => "send_message",
                    "method" => "post",
                    "action" => "sendMessage"
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class='col-md-3'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <a class='btn btn-info btn-xs pull-right new_message_button'>New Message</a>
                <h3 class='panel-title'>Inbox</h3>
            </div>
            <div class="list-group">
                <?php echo $messages; ?>
            </div>
        </div>
    </div>
    <div class='col-md-9'>
        <div id="message_wrapper">
            <?php
            if ($guid) {
                echo display("pages/message", array(
                    "guid" => $guid,
                    "active_guid" => $guid
                ));
            }
            ?>
        </div>
    </div>
</div>
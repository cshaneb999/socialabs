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

$value_users_guid = array();
$value = array();

$users = Vars::get('users'); // Array of user guids
$label = Vars::get('label'); // Title for modal

$button_label = Vars::get("button_label");
if (!$button_label) {
    $button_label = "Pick Users";
}

$name = Vars::get("name");

$value_users_gallery = NULL;

$value = Vars::get("value");
if (!$value) {
    $value = array();
}
if (!empty($value)) {
    $value_users_gallery = display("output/user_gallery", array(
        "guids" => $value
    ));
}
foreach ($value as $user_guid) {
    $value_users_guid[] = getEntity($user_guid);
}
$value_users_gallery = viewEntityList($value_users_guid, "gallery", null, "avatar_gallery", false, "medium");

if ($users) {
    $gallery = viewEntityList($users, "gallery", null, "user_picker avatar_gallery alert alert-danger", false, "medium");
} else {
    $gallery = NULL;
}
$json_value = json_encode($value);


echo "<div class='form-group'>";
echo "<label>$label</label>";
echo "<div class='well clearfix'>";
echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#user_select_modal' onclick='userpicker.update_modal_selections($json_value);'>";
echo $button_label;
echo "</button>";
echo "<div id='selected_users'>";
echo $value_users_gallery;
echo "</div>";
if (isset($value)) {
    echo "<input type='hidden' name='$name' id='userpicker_input' value='" . implode(',', $value) . ";'/>";
}
echo "</div>";
echo "</div>";
echo "<div class='modal fade' id='user_select_modal' tabindex='-1' role='dialog' aria-labelledby='userpicker' aria-hidden='true'>";
echo "<div class='modal-dialog'>";
echo "<div class='modal-content'>";
echo "<div class='modal-header'>";
echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
echo "<h4 class='modal-title' id='userpicker'>$label</h4>";
echo "</div>";
echo "<div class='modal-body'>";
echo "<div class='user_picker_container clearfix'>";
echo $gallery;
echo "</div>";
echo "</div>";
echo "<div class='modal-footer'>";
echo "<button type='button' class='btn btn-warning' data-dismiss='modal'>Cancel</button>";
echo "<button type='button' class='btn btn-primary' onclick='userpicker.save();'>Save</button>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

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

$show_profile_menu = Vars("show_profile_menu");

$guid = pageArray(1);
if (is_numeric($guid)) {
    $owner = getEntity($guid);
    if (!is_a($owner, "SociaLabs\User")) {
        $guid = Vars("guid");
        $owner = getEntity($guid);
        if (!is_a($owner, "SociaLabs\User")) {
            if (loggedIn()) {
                $guid = getLoggedInUserGuid();
                $owner = getLoggedInUser();
            }
        }
    }
} else {
    $guid = Vars("guid");
    $owner = getEntity($guid);
    if (!is_a($owner, "SociaLabs\User")) {
        if (loggedIn()) {
            $guid = getLoggedInUserGuid();
            $owner = getLoggedInUser();
        }
    }
}




$url = getSiteURL();

$profile_info_editor = NULL;
$profile_info = NULL;
$truncate = NULL;
if ($show_profile_menu) {
    $profile_menu = MenuItem::getAll("profile");
} else {
    $profile_menu = NULL;
}
$profile_type = $owner->profile_type;
if (!$profile_type) {
    $profile_type = "default";
}
$avatar = $owner->icon(LARGE);
$profile_fields = ProfileField::get($profile_type);
$status = $owner->status ? "<div class='well well-sm'>$owner->status</div>" : "";
foreach ($profile_fields as $name => $values) {
    $field_type = $values['field_type'];
    $footer = NULL;
    $label = $values['label'];
    $value = $owner->$name;
    if ($value) {
        if (($values['field_type'] != "textarea") && ($values['field_type'] != 'editor') && ($values['field_type'] != 'hidden')) {

            $value_text = display("output/$field_type", array(
                "value" => $value,
                "label" => $label
            ));
            $profile_info .= <<<HTML
            <tr>
                <td>$value_text</td>
            </tr>
HTML;
        } elseif ($values['field_type'] != "hidden") {
            $truncate = NULL;
            if (strlen($owner->$name) > 120) {
                $truncate = truncate($owner->$name);
                $value = NULL;
                $footer = "<div class='panel-footer clearfix'>";
                $footer .= "<button class='btn btn-info btn-xs pull-right' data-toggle='modal' data-target='#profile_info_editor_$name'><i class='fa fa-eye'></i> Read More</button>";
                $footer .= "</div>";
            } else {
                $truncate = NULL;
                $value = display("output/editor", array(
                    "value" => $value,
                    "label" => NULL
                ));
            }
            $profile_info_editor .= <<<HTML
            
        <label>$label</label>
        $truncate
        $value
        $footer
HTML;
            if (strlen($owner->$name) > 120) {

                $content = display("output/editor", array(
                    "value" => $owner->$name
                ));

                echo <<<HTML
<div class='modal fade' id='profile_info_editor_$name' tabindex='-1' role='dialog' aria-labelledby='profile_info_editor_{$name}_title' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='profile_info_editor_{$name}_title'>$label</h4>
            </div>
            <div class='modal-body' style='max-height:460px;overflow:auto;'>
                $content
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-warning' data-dismiss='modal'>Done</button>
            </div>
        </div>
    </div>
</div>
HTML;
            } else {
                $value = NULL;
            }
        }
    }
}
?>
<div class="panel panel-default profile_info_editor_panel">
    <table class='table table-hover'>
        <?php echo $profile_info; ?>
    </table>
    <div class="panel-body profile">
        <?php echo $profile_info_editor; ?>
        <ul class='nav nav-pills nav-stacked'>
            <?php echo $profile_menu; ?>
        </ul>
    </div>
</div>
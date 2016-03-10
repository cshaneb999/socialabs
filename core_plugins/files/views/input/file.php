<?php

/**
 * File Input
 *
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

$name = Vars::get("name");
$multiple = Vars::get("multiple");
$label = Vars::get("label");
if ($label) {
    $label = "<label>" . $label . "</label>";
}
$select_file_label = translate("file_input:select_file");
$remove_label = translate("file_input:remove");
if ($multiple) {
    $help_block = <<<HTML
<span class="help-block">
    Multiple files can be uploaded.
</span>
HTML;
} else {
    $help_block = "";
}
echo <<<HTML
$label
<div class="input-group" style='margin-bottom:15px;'>
    <span class="input-group-btn">
        <span class="btn btn-primary btn-file">
            $select_file_label <input type="file" name="$name" $multiple>
        </span>
    </span>
    <input type="text" class="form-control" readonly>
</div>
$help_block
HTML;

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

$value = Vars::get("value");
$name = Vars::get("name");
$label = Vars::get("label");
$required = Vars::get("required");
if ($required) {
    $required = "required='required'";
} else {
    $required = "";
}
$disabled = Vars::get("disabled");
$autocomplete = Vars::get("autocomplete");
$class = Vars::get("class");
echo <<<HTML
<div class='form-group'>
    <label for="$name">$label</label>
    <input name="$name" type="text" class="$class" value="$value" $required $disabled autocomplete="$autocomplete">
</div>
HTML;

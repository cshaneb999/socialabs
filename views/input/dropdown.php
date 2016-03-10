<?php

/**
 * Dropdown Input
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

$options = NULL;
$field_value = Vars::get("value");
if (!$field_value) {
    $field_value = Vars::get('name');
}
$name = Vars::get("name");
$label = Vars::get("label");
$options_values = Vars::get("options_values");
if (is_array($options_values)) {
    $class = Vars::get("class");
    $args = arrayToArgs(Vars::get("args"));
    foreach ($options_values as $key => $value) {
        if ($field_value == $key) {
            $selected = "selected";
        } else {
            $selected = NULL;
        }
        $options .= <<<HTML
             <option value="$key" $selected>$value</option>
HTML;
    }
    $page = <<<HTML
<div class='form-group'>
HTML;
    if ($label) {
        $page .= <<<HTML
    <label for="$name">$label</label>
HTML;
    }
    $page .= <<<HTML
    <select $args class="$class bootstrap-select" name="$name">$options</select>
</div>
HTML;
    echo $page;
}

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
$style = Vars::get("style");
if ($required) {
    $required = "required='required'";
} else {
    $required = NULL;
}
$disabled = Vars::get("disabled");
if ($disabled) {
    $disabled = "disabled";
} else {
    $disabled = NULL;
}
$prepend = Vars::get("prepend");
$extend = Vars::get("extend");
$class = Vars::get("class");
$placeholder = Vars::get("placeholder");
$data = Vars::get("data");
$data = json_encode($data);
$body = "<div class='form-group'>";
if ($label) {
    $body .= "<label for='$name'>$label</label>&nbsp;";
}

if ($prepend || $extend) {
    $body .= "<div class='input-group'>";
}

if ($prepend) {
    $body .= "<div class='input-group-addon'>$prepend</div>";
}

$body .= "<input style='$style' name='{$name}_typeahead' placeholder='$placeholder'  type='text' class='$class' value='$value' autocomplete='off' $required $disabled>";
$body .= display("input/hidden", array(
    "name" => $name,
    "value" => $value,
    "label" => NULL
        ));

if ($extend) {
    $body .="<div class='input-group-addon'>$extend</div>";
}

if ($prepend || $extend) {
    $body .= "</div>";
}

$body .= "</div>";

echo $body;
?>
<script>
    $("document").ready(function () {
        $("[name='<?php echo $name; ?>_typeahead']").typeahead(
                {
                    source: <?php echo $data; ?>
                }
        );
        $("[name='<?php echo $name; ?>_typeahead']").change(function () {
            var submit_button = $(this).closest("form").find(':submit');
            var current = $("[name='<?php echo $name; ?>_typeahead']").typeahead("getActive");
            if (current) {
                // Some item from your model is active!
                if (current.name == $("[name='<?php echo $name; ?>_typeahead']").val()) {
                    var value = current.value;
                    $("[name='<?php echo $name; ?>']").val(value);
                    submit_button.removeClass("disabled");
                    // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                } else {
                    $("[name='<?php echo $name; ?>']").val("");
                    submit_button.addClass("disabled");
                    // This means it is only a partial match, you can either add a new item 
                    // or take the active if you don't want new items
                }
            } else {
                // Nothing is active so it is a new value (or maybe empty value)
            }
        });
    });
</script>
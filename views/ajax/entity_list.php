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

$params = Vars("params");
$id = Vars("id");
$wrapper = Vars("wrapper");
$wrapper_class = Vars("wrapper_class");
$title = Vars("title");
$panel = Vars("panel");
$wrapper_id = "entity_list_$id";
if (!$wrapper) {
    $wrapper = "div";
}
$wrapper_header = "<$wrapper class='$wrapper_class' id='$wrapper_id'>";
$wrapper_footer = "</$wrapper>";
if (!isset($params['limit'])) {
    $params['limit'] = 10;
}
if (!isset($params['offset'])) {
    $params['offset'] = 0;
}
$body = listEntities($params);
$count_shown = ($params['offset'] + 1) * $params['limit'];

$params2 = $params;

unset($params2['limit']);
unset($params2['offset']);
$params2['count'] = true;
$count = getEntities($params2);
if ($count >= $count_shown) {
    $params['offset'] = $params['offset'] + $params['limit'];
    $params = json_encode($params);
    $button = "<button class='show_more_entities btn btn-danger btn-xs pull-right' data-count='$count' data-count_shown='$count_shown' data-params='$params' data-id='$wrapper_id'>More</button>";
} else {
    $button = NULL;
}
if ($title) {
    $title = <<<HTML
<div class='panel-heading'>
    $title
</div>
HTML;
}
if ($panel) {
    echo <<<HTML
<div class='panel panel-default'>
    $title
    <div class='panel-body'>
        $wrapper_header
            $body
            $button
        $wrapper_footer
    </div>
</div>
HTML;
} else {
    echo <<<HTML
        $wrapper_header
            $body
            $button
        $wrapper_footer
HTML;
}
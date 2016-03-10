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

adminGateKeeper();

$guid = Vars::get('guid');
$field = getEntity($guid);
$icon = $field->iconURL();
$body = <<<HTML
<div class="well">
        
    <div class="media">
        <div class="media-left">
                <img class="media-object img-rounded" src="$icon" style='width:64px;'/>
        </div>
        <div class="media-body">
            <span>Label: $field->label</span><br/>
            <span>Name:  $field->name</span><br/>
            <span>Type:  $field->field_type</span><br/>
            <span>Required:  $field->required</span><br/>
        </div>
        <div class="media-right">
            <div class='btn-group'>
                <a class="btn btn-danger" href="index.php?action=remove_profile_field&guid=$field->guid">
                    <i class='fa fa-times'></i>
                </a>
            </div>
        </div>
    </div>
</div>
HTML;
echo $body;

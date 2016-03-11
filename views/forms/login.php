<?php

/**
 * Login Form
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

echo "<div class='clearfix'>";
$referer = NULL;
if (isset($_GET['referer'])) {
    $referer = $_GET['referer'];
}
if ($referer) {
    echo display("input/hidden", array(
        "name"  => "referer",
        "value" => $referer
    ));
}
echo display("input/email", array(
    "name"        => "email",
    "label"       => NULL,
    "class"       => "form-control input-lg",
    "placeholder" => translate("login:form:label:email"),
    "value"       => NULL
));
echo display("input/password", array(
    "name"        => "password",
    "label"       => NULL,
    "value"       => NULL,
    "class"       => "form-control input-lg",
    "placeholder" => translate("login:form:label:password")
));
echo display("page_elements/login_extension");

echo display("input/submit", array(
    "label" => translate("login:form:button"),
    "class" => "btn btn-success pull-right"
));
echo "<div class='form-group' style='margin-top:10px;'>";
echo "<a href='" . getSiteURL() . "ForgotPassword' class='text-center' style='line-height:34px;'>Forgot Your Password?</a>";
echo "</div>";
echo "</div>";

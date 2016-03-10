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

global $first_run;
$first_run = true;
$page = pageArray(0);
$title = Metatag::getMetatagText("title", $page);
$description = Metatag::getMetatagText("description", $page);

echo "<meta charset = 'utf-8'>";
echo "<meta http-equiv = 'X-UA-Compatible' content = 'IE=edge'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>";
echo "<title>$title</title>";
echo "<meta name='description' content='$description'>";
echo CSS::draw("external");
echo CSS::draw("internal");
$url = getSiteURL();
echo <<<HTML
<script src="{$url}assets/vendor/jquery/dist/jquery.min.js"></script>
HTML;
echo HeaderJS::draw();
echo "<!--[if lt IE 9]>";
echo "<script src = 'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>";
echo "<script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>";
echo "<![endif]-->";

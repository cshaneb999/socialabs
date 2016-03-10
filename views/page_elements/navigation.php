<?php
/**
 * Navigation
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

$attributes = getAttributes("navbar");
if (!$attributes) {
    $attributes = "class='navbar navbar-default navbar-fixed-top clearfix' role='navigation' id='slide-nav' style='min-height:80px;'";
}
$logo = getSiteLogo();
?>


<?php echo display("navigation:before"); ?>
<div <?php echo $attributes; ?>>
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-toggle"> 
                <span class="sr-only"><?php echo translate("nav:toggle_navigation"); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a href='<?php echo getSiteURL(); ?>' alt="<?php echo getSiteName(); ?>"><?php echo $logo; ?></a>
        </div>
        <div id="slidemenu">
            <?php echo display("page_elements/navbar_left"); ?>
            <ul class='nav navbar-nav navbar-right'>
                <?php
                echo MenuItem::getAll("header_right");
                ?>
            </ul>
            <ul class='nav navbar-nav navbar-right'>
                <?php
                echo MenuItem::getAll("header_left");
                echo Custompage::getMenuItems();
                ?>
            </ul>
            <?php echo display("page_elements/navbar_right"); ?>
        </div>
    </div>
</div>
<?php
echo display("navigation:after");
?>    
<div class="navbar navbar-default hidden-xs hidden-sm" role="navigation" style='margin-top:89px;'>
    <div class="container">
        <div class='row'>
            <ul class='nav navbar-nav navbar-left'>
                <?php echo getMenuItems("my_account"); ?>
            </ul>
            <ul class='nav navbar-nav navbar-right'>
                <?php echo getMenuItems("my_account_right"); ?>
            </ul>
        </div>
    </div>
</div>
<div id="page-content">

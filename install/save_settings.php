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

$error = isset($_GET['error']) ? $_GET['error'] : "";
$sitename = $_POST['sitename'];
$siteurl = $_POST['siteurl'];
$sitepath = $_POST['sitepath'];
$sitedatapath = $_POST['sitedatapath'];
$siteemail = $_POST['siteemail'];

if (!preg_match("~^(?:f|ht)tps?://~i", $siteurl)) {
    $siteurl = "http://" . $siteurl;
}

if (substr($siteurl, -strlen("/")) != "/") {
    $siteurl.= "/";
}
if (substr($sitepath, -strlen("/")) != "/") {
    $sitepath .= "/";
}
if (substr($sitedatapath, -strlen("/")) != "/") {
    $sitedatapath .= "/";
}
$token = bin2hex(\mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));

$sitename = addslashes($sitename);
$siteurl = addslashes($siteurl);
$sitepath = addslashes($sitepath);
$sitedatapath = addslashes($sitedatapath);
$siteemail = addslashes($siteemail);

$string = "<?php 
    
namespace SociaLabs;
    
define('SITENAME', '$sitename');
define('SITEURL', '$siteurl');
define('SITEPATH', '$sitepath');
define('SITEDATAPATH', '$sitedatapath');
define('SITEEMAIL','$siteemail');
define('SITESECRET','$token');
define('MEMCACHED_HOST', '127.0.0.1');
define('MEMCACHED_PORT', '11211');
define('SITELANGUAGE','en');
";


if (!is_writable(dirname(dirname(__FILE__)) . "/engine/settings.php")) {
    header("Location: save_dbase.php?error=Your engine folder is not writeable.  Please change it's permissions to 0777.&sitename=$sitename&siteurl=$siteurl&sitepath=$sitepath&sitedatapath=$sitedatapath&siteemail=$siteemail");
}

if (!file_exists($sitepath . "index.php")) {
    header("Location: save_dbase.php?error=Your site path is not correct.  Please check it and try again.&sitename=$sitename&siteurl=$siteurl&sitepath=$sitepath&sitedatapath=$sitedatapath&siteemail=$siteemail");
}

$fp = fopen(dirname(dirname(__FILE__)) . "/engine/settings.php", "w");

fwrite($fp, $string);

fclose($fp);

require_once($sitepath . "lib/shortcuts.php");
require_once($sitepath . "lib/helpers.php");
if (file_exists($sitepath . "vendor/autoload.php")) {
    require_once($sitepath . "vendor/autoload.php");
}

if (file_exists($sitepath . "vendor/ircmaxell/password-compat/lib/password.php")) {
    require_once($sitepath . "vendor/ircmaxell/password-compat/lib/password.php");
}
new Init();

header("Location:  ../index.php");

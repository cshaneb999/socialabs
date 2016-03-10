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
if (isset($_POST['dbhost'])) {
    $dbhost = $_POST['dbhost'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];
    $dbname = $_POST['dbname'];

    $test = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if (!$test) {
        header("Location: dbase.php?error=Could not connect to database.  Please verify your settings.&dbhost=$dbhost&dbuser=$dbuser&dbname=$dbname");
    }

    $string = "<?php 
    
namespace SociaLabs;
    
define('DBHOST', '$dbhost');
define('DBNAME', '$dbname');
define('DBUSER', '$dbuser');
define('DBPASS', '$dbpass');
";
    if (!file_exists(dirname(dirname(__FILE__)) . "/engine/dbase_settings.php")) {
        if (!is_writable(dirname(dirname(__FILE__)) . "/engine/")) {
            header("Location: dbase.php?error=Your engine folder is not writeable.  Please change it's permissions to 775.&dbhost=$dbhost&dbuser=$dbuser&dbname=$dbname");
        }

        $fp = fopen(dirname(dirname(__FILE__)) . "/engine/dbase_settings.php", "w");

        fwrite($fp, $string);

        fclose($fp);
    }
}
$sitename = isset($_GET['sitename']) ? $_GET['sitename'] : "";
$siteurl= isset($_GET['siteurl']) ? $_GET['siteurl'] : "";
$sitepath = isset($_GET['sitepath']) ? $_GET['sitepath'] : "";
$sitedatapath = isset($_GET['sitedatapath']) ? $_GET['sitedatapath'] : "";
$siteemail = isset($_GET['siteemail']) ? $_GET['siteemail'] : "";
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>SociaLabs Installation</title>
        <link href="../assets/vendor/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/bootswatch/paper.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php
        if ($error) {
            echo <<<HTML
            <div class='alert alert-danger'>
                <div class='container'>
                    <center>$error</center>
                </div>
            </div>
HTML;
        }
        ?>
        <div class='container'>
            <div class="page-header">
                <h1 class="text-center"><small>SociaLabs: Installation</small></h1>
                <p class="lead text-center">Step 2:  Site Configuration:</p>
            </div>
            <div class='row'>
                <div class='col-sm-6 col-sm-offset-3'>
                    <p class="lead">Please enter website details below:</p>
                    <form method='post' action='save_settings.php' style='margin-bottom:20px;'>
                        <div class="form-group">
                            <input type="text" name='sitename' class="form-control" id="sitename" placeholder="Site name" value='<?php echo $sitename; ?>' required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='siteurl' class="form-control" id="siteurl" placeholder="Site url" value='<?php echo $siteurl; ?>' required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='sitepath' class="form-control" id="sitepath" placeholder="Site path" value='<?php echo $sitepath; ?>' required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='sitedatapath' class="form-control" id="sitedatapath" placeholder="Site data path" value='<?php echo $sitedatapath; ?>' required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='siteemail' class="form-control" id="siteemail" placeholder="Site email" value='<?php echo $siteemail; ?>' required/>
                        </div>
                        <input type="submit" class="btn btn-default btn-success" value="Next">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
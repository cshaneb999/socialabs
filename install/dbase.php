<?php
$error = isset($_GET['error']) ? $_GET['error'] : "";
$dbhost = isset($_GET['dbhost']) ? $_GET['dbhost'] : "";
$dbuser = isset($_GET['dbuser']) ? $_GET['dbuser'] : "";
$dbname = isset($_GET['dbname']) ? $_GET['dbname'] : "";
?>
<!DOCTYPE html>
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
                <p class="lead text-center">Step 1:  Database configuration</p>
            </div>
            <div class='row'>
                <div class='col-sm-6 col-sm-offset-3'>
                    <p class="lead">Please enter your database details and credentials below:</p>
                    <form method='post' action='save_dbase.php' style='margin-bottom:20px;'>
                        <div class="form-group">
                            <input type="text" name='dbhost' class="form-control" id="dbhost" placeholder="Database hostname" value="<?php echo $dbhost; ?>" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='dbname' class="form-control" id="dbname" placeholder="Database name" value="<?php echo $dbname; ?>" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='dbuser' class="form-control" id="dbuser" placeholder="Database user" value="<?php echo $dbuser; ?>" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" name='dbpass' class="form-control" id="dbpass" placeholder="Database password"/>
                        </div>
                        <input type="submit" class="btn btn-default btn-success" value="Next">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
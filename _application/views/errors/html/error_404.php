<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://';
$fo = str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
$base_url = "$http" . $_SERVER['SERVER_NAME'] . "" . $fo;

$themeurl = $base_url . 'themes/backend/gentelella';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="shortcut icon" href="<?php echo $themeurl; ?>/images/favicon.png" />

        <title>404!</title>

        <!-- Bootstrap -->
        <link href="<?php echo $themeurl; ?>/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="<?php echo $themeurl; ?>/css/font-awesome.min.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="<?php echo $themeurl; ?>/css/custom.min.css" rel="stylesheet">
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- page content -->
                <div class="col-md-12">
                    <div class="col-middle">
                        <div class="text-center text-center">
                            <h1 class="error-number">404 !</h1>
                            <h2><?php echo $heading; ?></h2>
                            <p><?php echo $message; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /page content -->
            </div>
        </div>

    </body>
</html>

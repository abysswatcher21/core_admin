<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- NO INDEX MESIN PENCARI-->
        <meta name="googlebot" content="noindex" />
      	<meta name="googlebot" content="nofollow" />
      	<meta name="googlebot-news" content="noindex" />
      	<meta name="googlebot-news" content="nosnippet" />
      	<meta name="googlebot-news" content="nofollow" />
      	<meta name="robots" content="noindex" />
      	<meta name="robots" content="nofollow" />

        <title>Administrator - <?php echo $this->site_configuration['title']; ?></title>

        <!-- Bootstrap -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/font-awesome.min.css" rel="stylesheet">
        
        <link rel="shortcut icon" href="<?php echo THEMES_BACKEND; ?>/images/favicon.png" />
        
        <!-- jQuery -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/jquery.min.js"></script>
        <script src="<?php echo THEMES_BACKEND; ?>/js/bootstrap.min.js"></script>
        
        <!-- Custom Theme Style -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/custom.min.css" rel="stylesheet">
        
        <link rel="manifest" href="<?php echo base_url().'manifest.json';?>">
        <!-- ios support-->
        <link rel="apple-touch-icon" href="<?php echo THEMES_BACKEND.'/images/pwa-icons/icon-96x96.png'; ?>">
        <meta name="apple-mobile-web-app-status-bar" content="#aa7700">
		
    </head>

    <body class="login">

        <?php echo template_echo('content');?>

        <script>
            window.setTimeout(function() {
                $('.alert-danger').css("display","none");
                $('.alert-success').css("display","none");
            }, 10000);
        </script>
        <script>
            $(function(){
                $("#captcha_reload").click(function(){
                    var url = '<?php echo site_url(_login_uri . '/captcha'); ?>';
                    $("#captcha_image").attr('src', url + '?' + Math.random());
                });
            });
        </script>
    </body>
</html>

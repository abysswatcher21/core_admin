<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- NO INDEX MESIN PENCARI-->
        <meta name="googlebot" content="noindex" />
        <meta name="googlebot" content="nofollow" />
        <meta name="googlebot-news" content="noindex" />
        <meta name="googlebot-news" content="nosnippet" />
        <meta name="googlebot-news" content="nofollow" />
        <meta name="robots" content="noindex" />
        <meta name="robots" content="nofollow" />

        <title>Administrator -  <?php echo $this->site_configuration['title']; ?></title>

        <link rel="shortcut icon" href="<?php echo THEMES_BACKEND; ?>/images/favicon.png" />
        
        <link rel="manifest" href="<?php echo site_url('manifest.json'); ?>">
        
        <!-- Bootstrap -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/font-awesome.min.css" rel="stylesheet">
        
        <!-- NProgress -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/nprogress.css" rel="stylesheet">
        
        <!-- jQuery -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/jquery.min.js"></script>
        
        <!-- jQuery custom content scroller -->
        <link href="<?php echo THEMES_BACKEND; ?>/js/scrollbar/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="<?php echo THEMES_BACKEND; ?>/js/jquery.slimscroll.min.js?v=1.0.1"></script>
        
        <?php if($this->uri->segment(1) != 'dashboard'): ?>
        
            <!--bootstrap-daterangepicker-->
            <link href="<?php echo THEMES_BACKEND; ?>/css/bootstrap-datetimepicker.css" rel="stylesheet">
            <link type="text/css" rel="stylesheet" href="<?php echo THEMES_BACKEND; ?>/vendor/css/daterangepicker-bs3.css" />

            <!--bootstrap-jqgrid-->
            <link href="<?php echo THEMES_BACKEND; ?>/css/jquery-ui.css" rel="stylesheet">
            <!--<link href="<?php // echo THEMES_BACKEND;      ?>/css/ui.jqgrid-bootstrap.css" rel="stylesheet">-->

            <!-- jQuery UI-->
            <script src="<?php echo THEMES_BACKEND; ?>/js/jquery-ui.js"></script>

            <!-- bootstrap-daterangepicker -->
            <script src="<?php echo THEMES_BACKEND; ?>/vendor/js/moment.min.js"></script>
            <script src="<?php echo THEMES_BACKEND; ?>/vendor/js/daterangepicker.js"></script>
            <script src="<?php echo THEMES_BACKEND; ?>/js/validator.js"></script>

            <!--select2-->
            <script type="text/javascript" src="<?php echo THEMES_BACKEND; ?>/js/select2/select2.full.min.js"></script>
            <link type="text/css" rel="stylesheet" href="<?php echo THEMES_BACKEND; ?>/js/select2/select2.min.css" />

            <!-- flexigrid starts here -->
            <script type="text/javascript" src="<?php echo base_url(); ?>addons/flexigrid/js/flexigridx.js?v=1.0.1"></script>
            <script type="text/javascript" src="<?php echo base_url(); ?>addons/flexigrid/js/json2.js"></script>
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>addons/flexigrid/css/flexigrid.css?v=1.0.1" />
            <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>addons/flexigrid/button/style.css?v=1.0.1" />
            <!-- flexigrid ends here -->
        
        <?php endif; ?>

        <!-- Custom Theme Style -->
        <link href="<?php echo THEMES_BACKEND; ?>/css/custom.css?v=1.0.1" rel="stylesheet">

        <?php
        if (isset($extra_head_content)) {
            echo $extra_head_content;
        }
        ?>

        <style>
            /* Absolute Center Spinner */
            .custom-loading {
                position: fixed;
                z-index: 999;
                height: 100%;
                width: 100%;
                background-color: rgba(0,0,0,0.3);
            }

            .custom-loading span{
                content: url(<?php echo THEMES_BACKEND . '/images/loading.gif'; ?>);
                position: fixed;
                z-index: 9999;
                width: 80px;
                margin: auto;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
            }
            
            .page-title .title_left{
                width: 100%;
            }

        </style>
    </head>

    <?php
    //profile image
    if (isset($_SESSION['administrator_image']) && !empty($_SESSION['administrator_image']) && file_exists(_dir_administrator . $_SESSION['administrator_image'])) {
        $profile_image = $_SESSION['administrator_image'];
    } else {
        $profile_image = '_default.png';
    }

    $generate_menu = '';
    $generate_menu .= '
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
<div class="menu_section">
<ul class="nav side-menu">
';
    $generate_menu .= '
<li><a href="' . site_url('dashboard/dashboard1') . '"><i class="fa fa-home"></i>&nbsp;Dashboard</a></li>
';
    // cari root menu
    if (array_key_exists('0', $arr_menu)) {

        // urutkan root menu berdasarkan menu_order_by
        ksort($arr_menu[0]);

        // ekstrak root menu
        foreach ($arr_menu[0] as $rootmenu_sort => $rootmenu_value) {
            if (array_key_exists($rootmenu_value->administrator_menu_id, $arr_menu)) {
                $rootmenu_link = 'javascript:;';
            } else {
                if ($rootmenu_value->administrator_menu_link == '#') {
                    $rootmenu_link = '#';
                } else {
                    $rootmenu_link = base_url() . $rootmenu_value->administrator_menu_link;
                }
            }

            $sub_menu = "";
            $generate_submenu = "";

            // cari submenu 1
            if (array_key_exists($rootmenu_value->administrator_menu_id, $arr_menu)) {

                // urutkan submenu 1 berdasarkan menu_order_by
                ksort($arr_menu[$rootmenu_value->administrator_menu_id]);

                // ekstrak submenu 1 yang par_id adalah menu_id dari root menu
                foreach ($arr_menu[$rootmenu_value->administrator_menu_id] as $submenu_1_sort => $submenu_1_value) {

                    if ($submenu_1_value->administrator_menu_link == '#') {
                        $submenu_1_link = '#';
                    } else {
                        $submenu_1_link = base_url() . $submenu_1_value->administrator_menu_link;
                    }

                    $sub_menu .= '<li title="' . $submenu_1_value->administrator_menu_description . '"><a href="' . $submenu_1_link . '"><i class="' . $submenu_1_value->administrator_menu_class . '"></i>&nbsp;' . $submenu_1_value->administrator_menu_title . '</a></li>';
                }
            }

            if ($sub_menu == '') {
                $generate_menu .= '<li title="' . $rootmenu_value->administrator_menu_description . '"><a href="' . $rootmenu_link . '"><i class="' . $rootmenu_value->administrator_menu_class . '"></i>&nbsp;' . $rootmenu_value->administrator_menu_title . '</a></li>';
            } else {
                $generate_submenu = '<ul class="nav child_menu">' . $sub_menu . '</ul>';

                $generate_menu .= '<li title="' . $rootmenu_value->administrator_menu_description . '"><a href="' . $rootmenu_link . '"><i class="' . $rootmenu_value->administrator_menu_class . '"></i>&nbsp;' . $rootmenu_value->administrator_menu_title . ' <span class="fa fa-chevron-down"></span></a>';

                $generate_menu .= $generate_submenu;
                $generate_menu .= '</li>';
            }
        }
    }
    $generate_menu .= '<li><a href="' . base_url() . _logout_uri . '"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></li>';

    $generate_menu .= '
</ul>
</div>
</div>
';
    ?>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col menu_fixed">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="<?php echo site_url('dashboard/dashboard1'); ?>" class="site_title">
                                <i class="fa fa-home"></i>
                                <span style="font-size: 12pt"><?php echo $this->site_configuration['title']; ?></span>
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu profile quick info -->
                        <div class="profile clearfix">
                            <div class="profile_pic">
                                <img src="<?php echo base_url() . 'media/' . _dir_administrator . '50/50/' . $profile_image; ?>" alt="..." class="img-circle profile_img">
                            </div>
                            <div class="profile_info">
                                <span style="color: #ffcf3b;">Welcome,</span>
                                <h2><?php echo $_SESSION['administrator_name']; ?></h2>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- /menu profile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <?php echo $generate_menu; ?>
                        <!-- /sidebar menu -->

                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-navicon"></i></a>
                            </div>

                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="<?php echo base_url() . 'media/' . _dir_administrator . '50/50/' . $profile_image; ?>" alt=""><?php echo $_SESSION['administrator_name']; ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><a id="mProfile" href="<?php echo site_url('profile/systems/profile'); ?>"><i class="fa fa-user pull-right"></i> My Profile</a></li>
                                        <li><a href="<?php echo site_url('profile/systems/password'); ?>"><i class="fa fa-lock pull-right"></i>  Change Password</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo site_url(_logout_uri); ?>"><i class="fa fa-sign-out pull-right"></i> Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="right_col" role="main" style="min-height: 1054px;">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>dashboard/dashboard1"><i class="fa fa-home"></i>&nbsp; Dashboard</a></li>
                        <?php
                        if (isset($arr_breadcrumbs)) {
                            if (is_array($arr_breadcrumbs)) {
                                $i = 1;
                                foreach ($arr_breadcrumbs as $breadcrumbs_title => $breadcrumbs_links) {
                                    if ($breadcrumbs_links != '#') {
                                        $breadcrumbs_links = base_url() . $breadcrumbs_links;
                                    }
                                    if ($i == count($arr_breadcrumbs)) {
                                        echo '<li class="active">' . $breadcrumbs_title . '</li>';
                                    } else {
                                        echo '<li><a href="' . $breadcrumbs_links . '">' . $breadcrumbs_title . '</a></li>';
                                    }
                                    $i++;
                                }
                            }
                        }
                        ?>
                    </ul>
                    <!-- /top navigation -->
                    <div id="mContent">
                        <?php
                        if (isset($_SESSION['confirmation'])) {
                            echo $_SESSION['confirmation'];
                        }
                        ?>
                        <?php echo template_echo('content'); ?>
                    </div>
                </div>
                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        <?php echo $this->site_configuration['footer']; ?>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/fastclick.js"></script>
        <!-- NProgress -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/nprogress.js"></script>
        <!-- Custom Theme Scripts -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/custom.js?v=1.0.3"></script>
        <!-- jQuery custom content scroller -->
        <script src="<?php echo THEMES_BACKEND; ?>/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
        <script>
            $(document).ready(function () {
                $(".custom-loading").hide();
            });
            $(document).ajaxComplete(function(event, XMLHttpRequest, ajaxOptions){
                let ajaxResponseText = XMLHttpRequest.responseText;
                
                let arrResponse = ajaxResponseText.split('#');
                if (arrResponse[0] === 'expired') {
                    alert('Your session has ended!');
                    window.location.href = '<?php echo site_url(_login_uri); ?>';
                }
                if(arrResponse[0] === 'Unauthorized'){
                    alert('You are not allowed!');
                }
            });
            $(document).ajaxStart(function () {
                NProgress.start();
                $(".custom-loading").show();
            });

            $(document).ajaxStop(function () {
                NProgress.done();
                $(".custom-loading").hide();
            });
        </script>
    </body>
</html>

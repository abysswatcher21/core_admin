<!DOCTYPE html>

<!-- 
Template Name: Omember - OmarKenthir - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0
Version: 1.0
Author: @myrindaman
Website: http://www.netsolmind.com/
Email: designer@esoftdream.net
-->

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>Administrator -  <?php echo $this->site_configuration['title']; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="MobileOptimized" content="320" />

        <!-- BEGIN GLOBAL MANDATORY STYLES -->          
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/font-awesome.min.css" />
        <!--<link type="text/css" rel="stylesheet" href="<?php // echo $themes_url; ?>/css/bootstrap.css" />-->
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/bootstrap.min.css" />
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN THEME STYLES --> 
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/jquery.ui.theme.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/custom.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/style-responsive.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/css/table.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/js/daterangepicker/daterangepicker-bs3.css" />
        <!-- END THEME STYLES -->

        <link rel="shortcut icon" href="<?php echo $themes_url; ?>/images/favicon.ico" />
        
        <!-- LOAD JAVASCRIPT LIBRARY -->
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery.easing.1.3.js"></script>
        <!--<script type="text/javascript" src="<?php // echo $themes_url; ?>/js/bootstrap.js"></script>-->
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/twitter-bootstrap-hover-dropdown.min.js"></script>

        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery.slimscroll.min.js"></script>
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/select2/select2.full.min.js"></script>
        <link type="text/css" rel="stylesheet" href="<?php echo $themes_url; ?>/js/select2/select2.min.css" />
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery.blockui.min.js"></script>  
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/daterangepicker/moment.min.js"></script>  
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/daterangepicker/daterangepicker.js"></script>  
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery.cookie.min.js"></script>
        <!--<script type="text/javascript" src="<?php echo $themes_url; ?>/js/jquery.uniform.min.js"></script>-->
        <script type="text/javascript" src="<?php echo $themes_url; ?>/js/script.js"></script>	
        
        <!-- flexigrid starts here -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>addons/flexigrid/css/flexigrid.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>addons/flexigrid/button/style.css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/flexigrid/js/flexigridx.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/flexigrid/js/json2.js"></script>
        <!-- flexigrid ends here -->
        
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/js/checkbox.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/js/accounting.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/js/accounting.function.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/highcharts/highcharts.js"></script>
        <!--<script type="text/javascript" src="<?php echo base_url(); ?>addons/highcharts/highcharts-3d.js"></script>-->
        <!--<script type="text/javascript" src="<?php echo base_url(); ?>addons/highcharts/modules/data.js"></script>-->
        <!--<script type="text/javascript" src="<?php echo base_url(); ?>addons/highcharts/modules/drilldown.js"></script>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>addons/highcharts/modules/exporting.js"></script>
        
        <?php
        if(isset($extra_head_content)) {
            echo $extra_head_content;
        }
        ?>
        
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed">
        <?php
        //profile image
        if ($this->session->userdata('administrator_image') != '' && file_exists(_dir_administrator . $this->session->userdata('administrator_image'))) {
            $profile_image = $this->session->userdata('administrator_image');
        } else {
            $profile_image = '_default.png';
        }
        
        $generate_menu = '';
        $generate_menu .= '<ul class="page-sidebar-menu">';
        $generate_menu .= '
            <li>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler"></div>
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            </li>
            <li class="start active ">
                <a href="' . base_url() . 'backend/dashboard/show">
                    <i class="fa fa-home"></i> 
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
        ';

        // cari root menu
        if (array_key_exists('0', $arr_menu)) {

            // urutkan root menu berdasarkan menu_order_by
            ksort($arr_menu[0]);

            // ekstrak root menu
            foreach ($arr_menu[0] as $rootmenu_sort => $rootmenu_value) {
                if(array_key_exists($rootmenu_value->administrator_menu_id, $arr_menu)) {
                    $rootmenu_link = 'javascript:;';
                } else {
                    if($rootmenu_value->administrator_menu_link == '#') {
                        $rootmenu_link = '#';
                    } else {
                        $rootmenu_link = base_url() . $rootmenu_value->administrator_menu_link;
                    }
                }
                $generate_menu .= '
                    <li class="" title="' . $rootmenu_value->administrator_menu_description . '">
                        <a href="' . $rootmenu_link . '">
                            <i class="' . $rootmenu_value->administrator_menu_class . '"></i> 
                            <span class="title">' . $rootmenu_value->administrator_menu_title . '</span>
                            <span class="arrow"></span>
                        </a>
                ';

                // cari submenu 1
                if(array_key_exists($rootmenu_value->administrator_menu_id, $arr_menu)) {
                    $generate_menu .= '<ul class="sub-menu" style="display: none;">';

                    // urutkan submenu 1 berdasarkan menu_order_by
                    ksort($arr_menu[$rootmenu_value->administrator_menu_id]);

                    // ekstrak submenu 1 yang par_id adalah menu_id dari root menu
                    foreach ($arr_menu[$rootmenu_value->administrator_menu_id] as $submenu_1_sort => $submenu_1_value) {
                        if($submenu_1_value->administrator_menu_link == '#') {
                            $submenu_1_link = '#';
                        } else {
                            $submenu_1_link = base_url() . $submenu_1_value->administrator_menu_link;
                        }
                        
                        $generate_menu .= '<li title="' . $submenu_1_value->administrator_menu_description . '"><a href="' . $submenu_1_link . '">' . $submenu_1_value->administrator_menu_title . '</a></li>';
                    }
                    $generate_menu .= '</ul>';
                }
                $generate_menu .= '</li>';
            }
            $generate_menu .= '
                <li class="last">
                    <a href="' . base_url() . _backend_logout_uri . '">
                        <i class="fa fa-power-off"></i> 
                        <span class="title">Log Out</span>
                    </a>
                </li>
            ';
            $generate_menu .= '</ul>';
        }
        ?>
        <div class="header navbar navbar-inverse navbar-fixed-top">
            <!-- BEGIN TOP NAVIGATION BAR -->
            <div class="header-inner">
                <!-- PAGE INFO -->  
                <span class="info-brand"><?php echo $this->site_configuration['title']; ?></span>

                <!-- BEGIN LOGO -->  
                <a class="navbar-brand" href="<?php echo base_url(); ?>backend/dashboard/show">
                    <img src="<?php echo $themes_url; ?>/images/logo.png" alt="logo" class="img-responsive">
                </a>
                <!-- END LOGO -->

                <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
                <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <img src="<?php echo $themes_url; ?>/images/menu-toggler.png" alt="">
                </a> 
                <!-- END RESPONSIVE MENU TOGGLER -->

                <!-- BEGIN TOP NAVIGATION MENU -->
                <ul class="nav navbar-nav pull-right">
                    <li><h5 class="li-titles">ADMINISTRATOR - <?php echo $this->site_configuration['appname']; ?></h5></li>
                    <li class="devider">&nbsp;</li>

                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" src="<?php echo base_url() . 'media/' . _dir_administrator . '30/30/' . $profile_image; ?>">
                            <span class="username"><?php echo $this->session->userdata('administrator_name'); ?></span>
                            <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo base_url() . 'backend/systems/profile'; ?>"><i class="icon-user"></i> Profil</a></li>
                            <li><a href="<?php echo base_url() . 'backend/systems/password'; ?>"><i class="icon-lock"></i> Ubah Password</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo base_url() . _backend_logout_uri; ?>"><i class="icon-key"></i> Log Out</a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->

                </ul>
                <!-- END TOP NAVIGATION MENU -->

            </div>
            <!-- END TOP NAVIGATION BAR -->
        </div>

        <div class="clearfix"></div>

        <!-- CONTAINER -->
        <div class="page-container">
            <!-- SIDEBAR MENU -->
            <div class="page-sidebar navbar-collapse collapse" style="">
                <!-- BEGIN SIDEBAR MENU -->
                <?php echo $generate_menu; ?>
                <!-- END SIDEBAR MENU -->
            </div>

            <!-- MAIN CONTAINER -->
            <div class="page-content">            
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>backend/dashboard/show"><i class="icon-home"></i>&nbsp; Dashboard</a></li>
                    <?php
                    if(isset($arr_breadcrumbs)) {
                        if(is_array($arr_breadcrumbs)) {
                            $i = 1;
                            foreach($arr_breadcrumbs as $breadcrumbs_title => $breadcrumbs_links) {
                                if($breadcrumbs_links != '#') {
                                    $breadcrumbs_links = base_url() . $breadcrumbs_links;
                                }
                                if($i == count($arr_breadcrumbs)) {
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
                <?php
                if (isset($this->arr_flashdata['confirmation'])) {
                    echo $this->arr_flashdata['confirmation'];
                }
                ?>
                <?php $this->load->view($contents); ?>
            </div>
            <!-- END MAIN CONTAINER -->
        </div>
        <!-- END CONTAINER -->

        <script>
            jQuery(document).ready(function() {    
                App.init(); // initlayout and core plugins
            });
        </script>
        <!-- END JAVASCRIPTS -->


        <script type="text/javascript">
            $(function(){
                $('.slimScrollDiv').slimScroll({
                    //height: '250px'
                });
            });
        </script>

        <script>
            $(function() {
                $("#datepicker").datepicker();
            });
        </script>
        
        <?php
        if(isset($extra_foot_content)) {
            echo $extra_foot_content;
        }
        ?>
    </body>
</html>

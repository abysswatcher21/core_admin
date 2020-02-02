<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['media/assets/images/'] = "media/images/get_image/";
$route['media/assets/images/(:any)/(:any)/(:any)/(:any)'] = "media/images/get_image/$1/$2/$3/$4";

$route[_login_uri] = 'auth/login';
$route[_logout_uri] = 'auth/logout';

$route[_login_uri . '/(:any)'] = "auth/login/$1";
$route[_logout_uri . '/(:any)'] = "auth/logout/$1";

$route['master/product-supplier'] = "master/Product_supplier";
$route['master/product-supplier/(:any)'] = "master/Product_supplier/$1";
$route['master/product-supplier/(:any)/(:num)'] = "master/Product_supplier/$1/$2";
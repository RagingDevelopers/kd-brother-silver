<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['master/city/edit/(:any)'] = 'master/city/index/edit/$1';
$route['master/bank/edit/(:any)'] = 'master/bank/index/edit/$1';
$route['master/stamp/edit/(:any)'] = 'master/stamp/index/edit/$1';
$route['master/unit/edit/(:any)'] = 'master/unit/index/edit/$1';
$route['master/process/edit/(:any)'] = 'master/process/index/edit/$1';
$route['master/account_type/edit/(:any)'] = 'master/account_type/index/edit/$1';
$route['registration/user/edit/(:any)'] = 'registration/user/index/edit/$1';
$route['registration/customer/edit/(:any)'] = 'registration/customer/index/edit/$1';
$route['master/category/edit/(:any)'] = 'master/category/index/edit/$1';
$route['master/item/edit/(:any)'] = 'master/item/index/edit/$1';
$route['master/row_material_type/edit/(:any)'] = 'master/row_material_type/index/edit/$1';
$route['master/row_material/edit/(:any)'] = 'master/row_material/index/edit/$1';
$route['master/metal_type/edit/(:any)'] = 'master/metal_type/index/edit/$1';
$route['manufacturing/garnu/edit/(:any)'] = 'manufacturing/garnu/index/edit/$1';
$route['manufacturing/pre_garnu/edit/(:any)'] = 'manufacturing/pre_garnu/index/edit/$1';
$route['sales/report'] = 'sales/getReport';
/* Daybook Report Route */
$route['report/daybook']['get'] = 'report/daybook/index';
$route['report/daybook']['post'] = 'report/daybook/search';

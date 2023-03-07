<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['apiLink/(:any)'] = 'apiCont/test/$1';


//CHECKOUT ROUTES//

$route['userlogin'] = 'CheckOutApi/LogIn';
$route['gettransaction'] = 'CheckOutApi/getTransaction';
$route['getpackno'] = 'CheckOutApi/getPackageNo';
$route['searchbarcode'] = 'CheckOutApi/searchBarcode';
$route['changestat'] = 'CheckOutApi/changeTranStat';
$route['loadforpickup'] = 'CheckOutApi/loadForPickup';
$route['loadfordelivery'] = 'CheckOutApi/loadForDelivery';
$route['loadhistory'] = 'CheckOutApi/loadHistory';
$route['changeuserpassword'] = 'CheckOutApi/changeUserPassword';
$route['searchhistory'] = 'CheckOutApi/searchHistory';




$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
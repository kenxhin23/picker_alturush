<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

// $db['default'] = array(
// 	'dsn'	=> '',
// 	'hostname' => 'localhost',
// 	// 'username' => 'ecom',
// 	// 'password' => 'ecom2020',
// 	// 'database' => 'fe_ec',
// 	'username' => 'southp20_ecom1',
// 	'password' => '7PdY-_m5vMcP',
// 	'database' => 'southp20_ecom1',
// 	'dbdriver' => 'mysqli',
// 	'port' => 3306,
// 	'dbprefix' => '',
// 	'pconnect' => FALSE,
// 	'db_debug' => (ENVIRONMENT !== 'production'),
// 	'cache_on' => FALSE,
// 	'cachedir' => '',
// 	'char_set' => 'utf8',
// 	'dbcollat' => 'utf8_general_ci',
// 	'swap_pre' => '',
// 	'encrypt' => FALSE,
// 	'compress' => FALSE,
// 	'stricton' => FALSE,
// 	'failover' => array(),
// 	'save_queries' => TRUE
// );

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => '172.16.163.2',
	'port' => '3307',
	'username' => 'alturush',
	'password' => 'alturush',
	'database' => 'alturush',
	//'database' => 'fe_commerce2',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
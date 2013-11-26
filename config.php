<?php

	//
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	//
	session_start();
	
	//
	define('INCLUDE_DIRECTORY', dirname(__DIR__) . '/');
	
	//
	define('CLASS_DIRECTORY', dirname(__FILE__) . '/');
	
	//default log path
	define('DEFAULT_LOG_PATH', $_SERVER['DOCUMENT_ROOT'] . '/cb-admin/logs/' . date('m-d-Y') . '.log');

	//autoload composer
	//additional autoloads will be added to this file as well
	$autoloader = require '../vendor/autoload.php';
	
	//generic functions
	require_once INCLUDE_DIRECTORY . '/functions.php';

	//dependency injection container
	$config = new config();
	
	//slim config
	$config['mode'] = 'production';
	
	//host name
/*
	$config['host_name'] = get_current_host_name();
	
	//domain name
	$config['domain_name'] = get_current_domain() . '/';
	
	//url
	$config['url'] = get_current_url();
*/
	
	//admin url
	$config['admin_url'] = get_current_domain() . '/cb-admin/';
	
	
	//title
	$config['title'] = 'cBoard';
	
	//encryption salt
	//a good spot to generate these salts is at http://clsc.net/tools/random-string-generator.php
	//check all boxes and watch out for single quotes!
	$db['salt'] = '-2zs9-__-ASo7L3i-53nwIlW0-btL-87';
	
	//write database
	$db['write']['type'] = 'mysql';
	$db['write']['host'] = 'internal-db.s139514.gridserver.com';
	$db['write']['port'] = 3306;
	$db['write']['db'] = 'db139514_slim_test';
	$db['write']['user'] = 'db139514_Guest';
	$db['write']['password'] = 'Guest_2012';
	$db['write']['persist'] = false;
	
	//read database
	$db['read']['type'] = 'mysql';
	$db['read']['host'] = 'internal-db.s139514.gridserver.com';
	$db['read']['port'] = 3306;
	$db['read']['db'] = 'db139514_slim_test';
	$db['read']['user'] = 'db139514_Guest';
	$db['read']['password'] = 'Guest_2012';
	$db['read']['persist'] = false;
	
	//set $db config to dependency injection container
	$config['db'] = $db;
	
	return $config;
	
?>
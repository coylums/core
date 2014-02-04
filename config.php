<?php

	set_include_path('/var/www');

	//
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	//$autoloader = require 'vendor/autoload.php';

	//dependency injection container
	//$config = new \core\config();
	
	//encryption salt
	//a good spot to generate these salts is at http://clsc.net/tools/random-string-generator.php
	//check all boxes and watch out for single quotes!
	$db['salt'] = '-2zs9-__-ASo7L3i-53nwIlW0-btL-87';

	//write database
	$db['write']['type'] = 'mysql';
	$db['write']['host'] = 'localhost';
	$db['write']['port'] = 3306;
	$db['write']['db'] = 'test_core';
	$db['write']['user'] = 'helpdesk';
	$db['write']['password'] = '42doodles';
	$db['write']['persist'] = false;

	//read database
	$db['read']['type'] = 'mysql';
	$db['read']['host'] = 'localhost';
	$db['read']['port'] = 3306;
	$db['read']['db'] = 'test_core';
	$db['read']['user'] = 'helpdesk';
	$db['read']['password'] = '42doodles';
	$db['read']['persist'] = false;
	
	//set $db config to dependency injection container
	$config['db'] = $db;

	return $config;
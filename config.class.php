<?php

	session_start();
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	//include setup
	define('INCLUDE_DIRECTORY', dirname(__DIR__) . '/');
	
	define('CLASS_DIRECTORY', dirname(__FILE__) . '/');
	
	//generic functions
	require_once INCLUDE_DIRECTORY . '/functions.php';
	
	//create autoloader
	require_once INCLUDE_DIRECTORY . '/autoloader.php';
	
	//site constants
	define('TITLE', 'WEBSITE TITLE');
	
	define('HOST_NAME', get_current_host_name());
	
	define('DOMAIN_NAME', get_current_domain() . '/');

	define('URL', get_current_url());

	//paths
	define('DEFAULT_LOG_PATH', $_SERVER['DOCUMENT_ROOT'] . '/logs/' . date('m-d-Y') . '.log');

	//security

	//Encryption salt
	//A good spot to generate these salts is at http://clsc.net/tools/random-string-generator.php
	//check all boxes and watch out for single quotes!
	define('SALT', '-2zs9-__-ASo7L3i-53nwIlW0-btL-87'); //32 characters

	class config {
		
		private $db_config;
		
		function __construct()
		{
		
			$this->db_config['salt'] = SALT;
			
			//master
			$this->db_config['master']['type'] = 'mysql';
			$this->db_config['master']['host'] = 'HOST.WEBSITE.COM';
			$this->db_config['master']['port'] = 3306;
			$this->db_config['master']['db'] = 'DB_NAME';
			$this->db_config['master']['user'] = 'DB_USERNAME';
			$this->db_config['master']['password'] = 'DB_PASSWORD';
			$this->db_config['master']['persist'] = false;
			
			//slave
			$this->db_config['slave']['type'] = 'mysql';
			$this->db_config['slave']['host'] = 'HOST.WEBSITE.COM';
			$this->db_config['slave']['port'] = 3306;
			$this->db_config['slave']['db'] = 'DB_NAME';
			$this->db_config['slave']['user'] = 'DB_NAME';
			$this->db_config['slave']['password'] = 'DB_PASSWORD';
			$this->db_config['slave']['persist'] = false;
			
		}
		
		function get_config_array()
		{
			
			return $this->db_config;
			
		}
		
	}
	
?>
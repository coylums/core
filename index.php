<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require 'source/model_classes/core.class.php';
	require 'source/model_classes/db.class.php';
	require 'source/model_classes/errors.class.php';

	require 'includes/classes/user.class.php';

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

	$config['db'] = $db;

	$db = new \core\db($config);

	$user = new \core\user($db);

	$user->put_value('is_enabled', 1);
	$user->put_value('security_group', 99);
	$user->put_value('notify_by_email', 1);
	$user->put_value('first_name', 'John');
	$user->put_value('last_name', 'Doe');
	$user->put_value('email_address', 'test2@email.com');
	$user->put_value('hash', 'test@email2.com');
	$user->put_value('password', 'password123');

	$query = $db->get_write_connection()->query("INSERT INTO users SET first_name = 'coy'");

	$query->execute();

	echo $user->save();

	//$this->assertGreaterThan(0, $user->save());


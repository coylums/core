<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require 'source/model_classes/db.class.php';
	require 'source/model_classes/core.class.php';

	require 'includes/classes/user.class.php';

	$config = require 'config.php';

	$new_db = new \core\db($config);

	$user = new \core\user($new_db);

	$user->put_value('is_enabled', 1);
	$user->put_value('security_group', 99);
	$user->put_value('notify_by_email', 1);
	$user->put_value('first_name', 'John');
	$user->put_value('last_name', 'Doe');
	$user->put_value('email_address', 'test2@email.com');
	$user->put_value('hash', 'test@email2.com');
	$user->put_value('password', 'password123');

	echo $user->save() . ' is the ID of the newly inserted user.';


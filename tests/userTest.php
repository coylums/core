<?php

	require 'source/model_classes/core.class.php';
	require 'source/model_classes/db.class.php';
	require 'source/model_classes/pagination.class.php';

	require 'includes/classes/user.class.php';

	class userTest extends \PHPUnit_Framework_TestCase {

		public function setUp()
		{

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

			$this->_config['db'] = $db;

			$this->_config['domain_name'] = 'http://www.test.com';

			$this->_db = new \core\db($this->_config);

			//insert 25 users
			for($i = 0; $i < 25; $i++)
			{

				$user = new \core\user($this->_db);

				$user->put_value('is_enabled', 1);
				$user->put_value('security_group', 99);
				$user->put_value('notify_by_email', 1);
				$user->put_value('first_name', 'John');
				$user->put_value('last_name', 'Doe');
				$user->put_value('email_address', 'test' . $i . '@email.com');
				$user->put_value('hash', 'test' . $i . '@email.com');
				$user->put_value('password', 'password123');

				$this->assertGreaterThan(0, $user->save());

			}

			$this->_user = new \core\user($this->_db);

		}

		public function testCreateUsers()
		{

			$user = $this->_user;

			$user->put_value('is_enabled', 1);
			$user->put_value('security_group', 99);
			$user->put_value('notify_by_email', 1);
			$user->put_value('first_name', 'John');
			$user->put_value('last_name', 'Doe');
			$user->put_value('email_address', 'testCreateUsers@email.com');
			$user->put_value('hash', 'testCreateUsers@email.com');
			$user->put_value('password', 'password123');

			$this->assertGreaterThan(0, $user->save());

		}

		public function testData()
		{

			//test get/set of core object

			$user = new \core\user($this->_db);

			$user->put_value('is_enabled', 1);
			$user->put_value('security_group', 99);
			$user->put_value('notify_by_email', 1);
			$user->put_value('first_name', 'John');
			$user->put_value('last_name', 'Doe');
			$user->put_value('email_address', 'testCreateUsers@email.com');
			$user->put_value('hash', 'testCreateUsers@email.com');
			$user->put_value('password', 'password123');

			$this->assertGreaterThan(0, $user->save());

			$beginning_email = $user->data('email_address');

			$user->data('email_address', 'testData@email.com');

			$this->assertEquals('testData@email.com', $user->data('email_address'));

			$user->data('email_address', $beginning_email);

			$this->assertEquals('testCreateUsers@email.com', $user->data('email_address'));

		}

		public function testValidData()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals(1, $user->validate_data(\core\DB::DB_INT, 1));
			$this->assertEquals(3.14, $user->validate_data(\core\DB::DB_FLOAT, 3.14));
			$this->assertEquals('1985-01-11', $user->validate_data(\core\DB::DB_DATE, '1985-01-11'));
			$this->assertEquals('12:00:00', $user->validate_data(\core\DB::DB_TIME, '12:00:00'));
			$this->assertEquals('1985-01-11 12:00:00', $user->validate_data(\core\DB::DB_DATETIME, '1985-01-11 12:00:00'));
			$this->assertEquals('a string', $user->validate_data(\core\DB::DB_STR, 'a string'));
			$this->assertEquals(true, $user->validate_data(\core\DB::DB_BOOL, true));
			$this->assertEquals('test@email.com', $user->validate_data(\core\DB::DB_EMAIL, 'test@email.com'));
			$this->assertEquals('http://www.google.com', $user->validate_data(\core\DB::DB_URL, 'http://www.google.com'));

		}

		public function testInvalidData()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals(false, $user->validate_data(\core\DB::DB_INT, 'not an integer'));
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_FLOAT, 'not a float'));
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_DATE, 'not a date'));
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_TIME, 'not a time'));
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_DATETIME, 'note a datetime'));
			//$this->assertEquals(false, $user->validate_data(\core\DB::DB_STR, 'not a string')); //not a string
			//what is not a string?
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_BOOL, 'not a boolean'));
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_EMAIL, 'not an email address'));
			$this->assertEquals(false, $user->validate_data(\core\DB::DB_URL, 'not a url'));

		}

		public function testLoad()
		{

			$user = new \core\user($this->_db);

			//default id for a new object is zero, this should fail
			$this->assertTrue(!$user->load());

		}

		public function testLoadByPrimary()
		{

			$user = new \core\user($this->_db);

			$this->assertTrue($user->load_by_primary(1));

		}

		public function testDeleteByPrimary()
		{

			$user = new \core\user($this->_db);

			$this->assertTrue($user->delete_by_primary(1));

		}

		public function testGetTable()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals('users', $user->get_table());

		}

		public function testGetDBVariables()
		{

			$user = new \core\user($this->_db);

			$this->assertArrayHasKey('id', $user->get_db_variables());

		}

		public function testGetValue()
		{

			$user = new \core\user($this->_db);

			$user->load_by_primary(1);

			$this->assertEquals('John', $user->get_value('first_name'));

		}

		public function testPutValue()
		{

			$user = new \core\user($this->_db);

			$user->load_by_primary(1);

			$user->put_value('first_name', 'Newman');

			$this->assertEquals('Newman', $user->get_value('first_name'));

		}

		public function testFindPrimary()
		{

			$user = new \core\user($this->_db);

			$this->assertGreaterThan(0, $user->find_primary('email_address', 'test1@email.com'));

		}

		public function testToggleEnable()
		{

			$user = new \core\user($this->_db);

			$user->load_by_primary(1);

			$this->assertEquals(1, $user->get_value('is_enabled'));

			$user->toggle_enable();

			$this->assertEquals(0, $user->get_value('is_enabled'));

		}

		public function testSave()
		{

			$user = $this->_user;

			$user->put_value('is_enabled', 1);
			$user->put_value('security_group', 99);
			$user->put_value('notify_by_email', 1);
			$user->put_value('first_name', 'John');
			$user->put_value('last_name', 'Doe');
			$user->put_value('email_address', 'test3@email.com');
			$user->put_value('hash', 'test3@email.com');
			$user->put_value('password', 'password123');

			$this->assertGreaterThan(0, $user->save());

		}

		public function testDump()
		{

			$user = new \core\user($this->_db);

			$user->load_by_primary(1);

			$user->dump();

		}

		public function testGetPrimaries()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals(25, count($user->get_primaries('', 0, 25, 'id')));

		}

		public function testGetPaginatedPrimaries()
		{

			$user = new \core\user($this->_db);

			$pagination = new \core\pagination($this->_db, $user, 1);

			$this->assertEquals(25, count($user->get_paginated_primaries($pagination)));

		}

		public function testGetDistinctPrimaries()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals(25, count($user->get_distinct_primaries('email_address')));

		}

		public function testGetAssocArrayForFieldName()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals(25, count($user->get_assoc_array_for_field_name('email_address')));

		}

		public function testGetCount()
		{

			$user = new \core\user($this->_db);

			$this->assertEquals(25, $user->get_count());

		}

		public function tearDown()
		{

			//Purge table of values, preparing for next test

			$query = 'TRUNCATE TABLE users; ALTER TABLE users AUTO_INCREMENT = 1;';

			$statement = $this->_db->get_write_connection()->prepare($query);

			$statement->Execute();

		}

	}

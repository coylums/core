<?php

	class userTest extends \PHPUnit_Framework_TestCase {

		public function setUp()
		{

			$config = $this->getMock('config');

			$db = $this->getMock('db');

			$user = $this->getMock('user', array('put_value', 'save'));

			$user->put_value('is_enabled', 1);
			$user->put_value('security_group', 99);
			$user->put_value('notify_by_email', 1);
			$user->put_value('first_name', 'John');
			$user->put_value('last_name', 'Doe');
			$user->put_value('email_address', 'test1@email.com');
			$user->put_value('hash', 'test1@email.com');
			$user->put_value('password', 'password123');

			$this->assertGreaterThan(0, $user->save());

		}

		public function testData()
		{

			$user = new user();

			$user->put_value('is_enabled', 1);
			$user->put_value('security_group', 99);
			$user->put_value('notify_by_email', 1);
			$user->put_value('first_name', 'John');
			$user->put_value('last_name', 'Doe');
			$user->put_value('email_address', 'test2@email.com');
			$user->put_value('hash', 'test@email2.com');
			$user->put_value('password', 'password123');

			$this->assertGreaterThan(0, $user->save());

		}

		public function testSave()
		{

			$user = new user();

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
	}

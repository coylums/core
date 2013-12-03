<?php

	namespace core;

	class user extends core
	{

		function __construct($_db, $_primary_id = 0)
		{

			parent::__construct($_db);

			$this->table = 'users';

			$this->db_variables = array(

				'id' 		=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT,
					'encrypt' => FALSE,
					'accessible' => DB::DB_READ,
					'primary_key' => TRUE
				),
				'is_enabled' 		=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'security_group' 		=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'notify_by_email' 		=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'first_name' 		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'last_name' 		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'email_address' 		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'hash' 		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'password' 		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR,
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'date_created' 		=> array(
					'data' => DB::DEFAULT_DATE,
					'type' => DB::DB_DATE,
					'encrypt' => FALSE,
					'accessible' => DB::DB_READ,
					'primary_key' => FALSE
				),
				'date_updated' 		=> array(
					'data' => DB::DEFAULT_DATE,
					'type' => DB::DB_DATE,
					'encrypt' => FALSE,
					'accessible' => DB::DB_READ,
					'primary_key' => FALSE
				),
				'date_disabled' 		=> array(
					'data' => DB::DEFAULT_DATE,
					'type' => DB::DB_DATE,
					'encrypt' => FALSE,
					'accessible' => DB::DB_READ,
					'primary_key' => FALSE
				)

			);

			if($_primary_id != 0)
			{

				return $this->load_by_primary($_primary_id);

			}

		}

		function save()
		{

			if(empty($this->db_variables['id']['data']))
			{

				$this->db_variables['date_created']['data'] = date('Y-m-d H:i:s');

			}

			$this->db_variables['date_updated']['data'] = date('Y-m-d H:i:s');

			return parent::save();

		}

	}
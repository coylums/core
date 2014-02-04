<?php

	namespace core;

	class db
	{

		//accessibility
		const DB_WRITE = 1;
		const DB_READ = 2;
		const DB_RW = 3;

		//default values for types
		const DEFAULT_INT = 0;
		const DEFAULT_DATETIME = '0000-00-00 00:00:00';
		const DEFAULT_DATE = '0000-00-00';
		const DEFAULT_TIME = '00:00:00';
		const DEFAULT_STR = '';
		const DEFAULT_EMAIL = '';
		const DEFAULT_URL = '';
		const DEFAULT_BOOL = FALSE;
		const DEFAULT_FLOAT = 0.00;

		//encryption options
		const DB_MCRYPT = 1;
		const DB_MD5 = 2;

		//data types
		const DB_INT = 1;
		const DB_DATETIME = 2;
		const DB_DATE = 4;
		const DB_TIME = 8;
		const DB_STR = 16;
		const DB_BOOL = 32;
		const DB_EMAIL = 64;
		const DB_URL = 128;
		const DB_FLOAT = 256;

		//internal class variables
		public $db_config;
		private $db_config_array;

		private $salt;

		private $read_connection;
		private $write_connection;

		//function __construct($_type, $_host, $_port, $_db, $_user, $_pass, $_persist, $salt)
		function __construct($_config)
		{

			$this->db_config = $_config;
			//$this->db_config_array = $_config->get_config_array();

			try
			{

				$this->read_connection = $this->get_db_read();
				$this->write_connection = $this->get_db_write();

			}
			catch (\PDOException $_e)
			{

				error_log("Error connecting to database.");

				die("Error connecting to database: " . $_e);

			}

			$this->salt = $this->db_config['db']['salt'];

		}

		function __destruct()
		{

			$this->read_connection = null;
			$this->write_connection = null;

			unset($this->read_connection);
			unset($this->write_connection);
			unset($this->salt);

		}

		public function get_db_read()
		{

			$_config_array = $this->db_config;

			$_type 		= $_config_array['db']['read']['type'];
			$_host		= $_config_array['db']['read']['host'];
			$_port 		= $_config_array['db']['read']['port'];
			$_db 			= $_config_array['db']['read']['db'];
			$_user 		= $_config_array['db']['read']['user'];
			$_password 	= $_config_array['db']['read']['password'];
			$_persist 		= $_config_array['db']['read']['persist'];

			return new \PDO($_type . ':host=' . $_host . ';port=' . $_port . ';dbname=' . $_db, $_user, $_password, array(\PDO::ATTR_PERSISTENT => $_persist));

		}

		public function get_read_connection()
		{

			return $this->read_connection;

		}

		public function get_db_write()
		{

			$_config_array = $this->db_config;

			$_type 		= $_config_array['db']['write']['type'];
			$_host		= $_config_array['db']['write']['host'];
			$_port 		= $_config_array['db']['write']['port'];
			$_db 			= $_config_array['db']['write']['db'];
			$_user 		= $_config_array['db']['write']['user'];
			$_password 	= $_config_array['db']['write']['password'];
			$_persist 		= $_config_array['db']['write']['persist'];

			return new \PDO($_type . ':host=' . $_host . ';port=' . $_port . ';dbname=' . $_db, $_user, $_password, array(\PDO::ATTR_PERSISTENT => $_persist));

		}

		public function get_write_connection()
		{

			return $this->write_connection;

		}

		function store($_table, $_variables)
		{

			$this->read_connection->beginTransaction(); //we want roll-back capability for write

			//generate SQL
			$_insert_columns = "(";
			$_insert_data = "(";
			$_update = "";
			$_insert = FALSE;
			$_position = 0;

			foreach($_variables as $_name => $_data_array)
			{

				if($_data_array['primary_key'])
				{

					//hang onto primary key data, but don't include in update/insert
					$_primary_key = $_name;
					$_primary_value = $_data_array['data'];

					if(empty($_primary_value))
					{

						$_insert = TRUE;

					}

				}
				else
				{

					//continue building sql variables
					$_insert_columns .= ($_position == 0 ? $_name : ", " . $_name);

					$_insert_data .= ($_position == 0 ? "?" : ", ?");

					$_update .= ($_position == 0 ? $_name . " = ?" : ", " . $_name . " = ?");

					$_position++;

				}
			}

			$_insert_columns .= ")";

			$_insert_data .= ")";

			$_query = '';

			if($_insert)
			{

				//we're creating a record
				$_query = "INSERT INTO `$_table` $_insert_columns VALUES $_insert_data";

			}
			else
			{

				//we're updating a record
				$_query = "UPDATE $_table SET $_update WHERE `$_primary_key` = ?";

			}

			$_position = 1;

			$_statement = $this->read_connection->prepare($_query);

			//decide if we are using additional db salt in encryption
			$_use_extra_salt = FALSE;

			if(isset($_variables['db']['salt']))
			{

				$_use_extra_salt = TRUE;

				$_extra_salt = $_variables['db']['salt']['data'];

			}

			//bind params to query
			foreach($_variables as $_name => $_data_array)
			{

				//unfortunately, can't do this in the previous foreach.
				if(!$_data_array['primary_key'])
				{

					$_newdata = $_data_array['data'];

					if($_data_array['encrypt'] & self::DB_MCRYPT)
					{

						//do encryption if needed
						if($_use_extra_salt)
						{

							$_newdata = $this->data_crypt($_newdata, $_extra_salt);

						}
						else
						{

							$_newdata = $this->data_crypt($_newdata);

						}

					}

					$_statement->bindValue($_position, $_newdata);

					$_position++;

				}

			}

			if(!$_insert)
			{

				//if it's an update, bind the condition
				$_statement->bindValue($_position, $_primary_value);

			}

			//finally! Execute...
			$_result = $_statement->Execute();

			//if no result, sql error
			if(empty($_result))
			{

				$_error = $_statement->errorInfo();

				$_message = '';

				foreach($_error as $_errorline)
				{

					$_message .= $_errorline;

				}

				error_log("Failed saving row: $_message");

				return false;

			}

			//make sure sane number of rows
			$_affected = $_statement->rowCount();

			if($_affected == 0)
			{

				error_log("No rows updated!");

				return false;

			}

			if($_affected > 1)
			{

				error_log("Affected more than one row! Attempting rollback...");

				$this->read_connection->rollBack();

				return false;

			}

			//all is good, commit and return
			$_last_id = $this->read_connection->lastInsertId();

			$this->read_connection->commit();

			if($_insert)
			{

				return $_last_id;

			}
			else
			{

				return true;

			}

		}

		function retrieve_one($_table, &$_variables)
		{

			//we're assuming at this point the primary key is set in variables to do a retrieve on
			foreach($_variables as $_name => $_data_array)
			{

				if($_data_array['primary_key'])
				{

					$_primary_key = $_name;

					$_primary_value = $_data_array['data'];

					break;

				}

			}

			$_statement = $this->read_connection->prepare("SELECT COUNT(*) FROM $_table WHERE $_primary_key = ?");

			$_statement->bindParam(1, $_primary_value);

			$_result_set = $_statement->Execute();

			if(empty($_result_set))
			{

				//SQL error
				$_error = $_statement->errorInfo();

				$_message = '';

				foreach($_error as $_errorline)
				{

					$_message .= $_errorline;

				}

				error_log("SQL failed: " . $_message);

				return false;

			}

			//no record, abort without wasting more time
			if($_statement->fetchColumn() != 1)
			{

				error_log('We got a number of results other than 1');

				return false;

			}

			//we have a record! Do the real query
			$_statement = $this->read_connection->prepare("SELECT * FROM $_table WHERE $_primary_key = ?");

			$_statement->bindParam(1, $_primary_value);

			$_result_set = $_statement->Execute();

			$_statement->setFetchMode(\PDO::FETCH_ASSOC);

			$_row = $_statement->fetch();

			$_use_extra_salt = FALSE;


			if(isset($_row['db']['salt']))
			{

				$_use_extra_salt = TRUE;

				$_extra_salt = $_row['db']['salt'];

			}

			foreach($_row as $_key => $_value)
			{

				if($_variables[$_key]['encrypt'] & self::DB_MCRYPT)
				{

					if($_use_extra_salt)
					{

						$_variables[$_key]['data'] = $this->data_decrypt($_value, $_extra_salt);

					}
					else
					{

						$_variables[$_key]['data'] = $this->data_decrypt($_value);

					}

				}
				else
				{

					$_variables[$_key]['data'] = $_value;

				}

			}

			return true;

		}

		function retrieve_all_primaries($_table, &$_variables, $_where = "", $_offset = "0", $_number_of_results = "25", $_order_by = "")
		{

			if(!count($_variables))
			{

				return false;

			}

			$_primary_array = array();

			$_statement = $this->read_connection->prepare("SELECT COUNT(*) FROM $_table");

			$_result_set = $_statement->Execute();

			foreach($_variables as $_name => $_data_array)
			{

				if($_data_array['primary_key'])
				{

					$_primary_key = $_name;

					$_primary_value = $_data_array['data'];

					break;

				}

			}

			if(!empty($_result_set))
			{

				//we have a record! Do the real query
				$_statement = $this->read_connection->prepare("SELECT $_primary_key FROM $_table $_where $_order_by LIMIT $_offset, $_number_of_results");

				$_result_set = $_statement->Execute();

				$_statement->setFetchMode(\PDO::FETCH_ASSOC);

				$_row = $_statement->fetchAll();

				foreach($_row as $_key => $_value)
				{

					$_primary_array[] = $_value[$_primary_key];

				}

			}

			return $_primary_array;

		}

		function retrieve_distinct_primaries($_table, $_distinct_key, $_where = "", $_offset = "0", $_number_of_results = "25", $_order_by = "")
		{

			$_primary_array = array();

			$_statement = $this->read_connection->prepare("SELECT DISTINCT COUNT(*) FROM $_table");

			$_result_set = $_statement->Execute();

			if(!empty($_result_set))
			{

				//we have a record! Do the real query
				$_statement = $this->read_connection->prepare("SELECT DISTINCT $_distinct_key FROM $_table $_where $_order_by LIMIT $_offset, $_number_of_results");

				$_result_set = $_statement->Execute();

				$_statement->setFetchMode(\PDO::FETCH_ASSOC);

				$_row = $_statement->fetchAll();

				foreach($_row as $_key => $_value)
				{

					$_primary_array[] = $_value[$_distinct_key];

				}

			}

			return $_primary_array;

		}

		function count_objects($_table, &$_variables, $_where = "")
		{

			$_count = 0;

			$_statement = $this->read_connection->prepare("SELECT COUNT(*) AS count FROM $_table $_where");

			$_result_set = $_statement->Execute();

			if(!empty($_result_set))
			{

				$_statement->setFetchMode(\PDO::FETCH_ASSOC);

				$_row = $_statement->fetchAll();

				$_count = $_row[0]["count"];

			}

			return $_count;

		}

		//Search for the primary given a field and value
		//Cannot be used to search for encrypted values...
		function search($_field, $_value, $_table, $_variables)
		{

			//we don't search on encrypted fields
			if($_variables[$_field]['encrypt'] == self::DB_MCRYPT)
			{

				return false;

			}

			//find the primary column name to return a value from
			$_primary_column = FALSE;

			foreach($_variables as $_name => $_column)
			{

				if($_column['primary_key'] == TRUE)
				{

					$_primary_column = $_name;
					break;

				}

			}

			if(empty($_primary_column))
			{

				return false;

			}

			//let's check the number of results we'll get...
			$_statement = $this->read_connection->prepare("SELECT COUNT($_primary_column) FROM $_table WHERE $_field = ?");

			$_statement->bindParam(1, $_value);

			$_result_set = $_statement->Execute();

			if(empty($_result_set))
			{

				//SQL error
				$_error = $_statement->errorInfo();

				$_message = '';

				foreach($_error as $_errorline)
				{

					$_message .= $_errorline;

				}

				error_log("SQL failed: " . $_message);

				return false;

			}

			//no record, abort without wasting more time
			if($_statement->fetchColumn()!=1)
			{

				error_log('We got a number of results other than 1');

				return false;

			}

			//we have a record! Do the real query
			$_statement = $this->read_connection->prepare("SELECT $_primary_column FROM $_table WHERE $_field = ?");

			$_statement->bindParam(1, $_value);

			$_result_set = $_statement->Execute();

			$_statement->setFetchMode(\PDO::FETCH_ASSOC);

			$_row = $_statement->fetch();

			return $_row[$_primary_column];

		}

		function delete_object($_table, $_variables)
		{

			$_primary_column = FALSE;

			foreach($_variables as $_name => $_column)
			{

				if($_column['primary_key'] == TRUE)
				{

					$_primary_column = $_name;
					break;

				}

			}

			if(empty($_primary_column))
			{

				return false;

			}

			//let's check the number of results we'll get...
			$_statement = $this->read_connection->prepare("SELECT COUNT($_primary_column) FROM $_table WHERE $_primary_column = ?");

			$_statement->bindParam(1, $_variables[$_primary_column]["data"]);

			$_result_set = $_statement->Execute();

			if(empty($_result_set))
			{

				//SQL error
				$_error = $_statement->errorInfo();

				$_message = '';

				foreach($_error as $_errorline)
				{

					$_message .= $_errorline;

				}

				error_log("SQL failed: " . $_message);

				return false;

			}

			//no record, abort without wasting more time
			if($_statement->fetchColumn()!=1)
			{

				error_log('We got a number of results other than 1');

				return false;

			}

			//we have a record! Do the real query
			$_statement = $this->write_connection->prepare("DELETE FROM $_table WHERE $_primary_column = ?");

			$_statement->bindParam(1, $_variables[$_primary_column]["data"]);

			if($_result_set = $_statement->Execute())
			{

				return true;

			}

		}

		//Encrypts $_data using a combination of class SALT (should be in config.inc.php) and a passed salt (not required)
		function data_crypt($_data, $salt = '')
		{

			if(!is_null($_data))
			{

				if(empty($this->salt))
				{

					error_log("class SALT is undefined! This is very bad...");

				}

				$_algo = MCRYPT_RIJNDAEL_192; //algorithm

				$_mode = MCRYPT_MODE_ECB;

				$_iv_size = mcrypt_get_iv_size($_algo, $_mode);

				$_iv = mcrypt_create_iv($_iv_size, MCRYPT_DEV_URANDOM);

				$_key_size = mcrypt_get_key_size('rijndael-192', 'ecb');

				$_code = substr($this->salt . $salt, ($_key_size * (-1)));

				$_data = mcrypt_encrypt($_algo, $_code, $_data, $_mode, $_iv);

			}
			else
			{

				$_data = $_data;

			}

			return $_data;//don't encrypt null or empty data

		}

		//Decrypts $_data using a combination of class SALT (should be in config.php) and a passed salt (not required)
		function data_decrypt($_data, $salt = '')
		{

			if(!is_null($_data))
			{

				if(empty($this->salt))
				{

					error_log("class SALT is undefined! This is very bad...");

				}

				$_algo = MCRYPT_RIJNDAEL_192; //algorithm

				$_mode = MCRYPT_MODE_ECB;

				$_iv_size = mcrypt_get_iv_size($_algo,$_mode);

				$_iv = mcrypt_create_iv($_iv_size, MCRYPT_DEV_URANDOM);

				$_key_size = mcrypt_get_key_size('rijndael-192', 'ecb');

				$_code = substr($this->salt . $salt, ($_key_size * (-1)));

				$_data = trim(mcrypt_decrypt($_algo, $_code, $_data, $_mode, $_iv), "\0");

			}
			else
			{

				$_data = $_data;

			}

			return $_data;

		}

	}

?>
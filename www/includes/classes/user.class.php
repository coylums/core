<?php

	//

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
				'is_enabled' 	=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'security_group_id' 	=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'notify_by_email' 	=> array(
					'data' => DB::DEFAULT_INT,
					'type' => DB::DB_INT, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'first_name'		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'last_name'		=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'email_address'			=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_EMAIL, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'hash'			=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR, 
					'encrypt' => DB::DB_MD5,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'password'			=> array(
					'data' => DB::DEFAULT_STR,
					'type' => DB::DB_STR, 
					'encrypt' => DB::DB_MD5,
					'accessible' => DB::DB_RW,
					'primary_key' => FALSE
				),
				'date_created' 	=> array(
					'data' => DB::DEFAULT_DATE,
					'type' => DB::DB_DATE, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_READ,
					'primary_key' => FALSE
				),
				'date_updated' 	=> array(
					'data' => DB::DEFAULT_DATE,
					'type' => DB::DB_DATE, 
					'encrypt' => FALSE,
					'accessible' => DB::DB_READ,
					'primary_key' => FALSE
				),
				'date_disabled' 	=> array(
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

		function login($_email, $_password)
		{
			
			//drop empty info...
			if(empty($_email) || empty($_password))
			{
				
				return false;
				
			}
			
			//look for this email address
			$_id = $this->find_primary('hash', md5($_email . SALT));
			
			if($this->load_by_primary($_id))
			{
									
				if($this->data('password') == md5($_password . SALT) && $this->data('is_enabled'))
				{
					
					return $_id;
					
				}
			
			}
			
			//failed to authenticate
			return false;
			
		}
	
		function save()
		{
			
			if(empty($this->db_variables['id']['data']))
			{
				
				$this->db_variables['date_created']['data'] = date('Y-m-d H:i:s');
				
			}
			
			$this->db_variables['date_updated']['data'] = date('Y-m-d H:i:s');
			
			parent::save();
			
		}
		
	}

?>
<?php
	
	
	

	class security_groups extends core_methods
	{
	
		function __construct($_db)
		{
		
			parent::__construct($_db);
			
			$this->class_name = 'security_group';
			$this->table = 'security_groups';
			$this->db = $_db;
			
		}
		
	}

?>
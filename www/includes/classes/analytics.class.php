<?php
	
	
	

	class analytics extends core_methods
	{
	
		function __construct($_db)
		{
		
			parent::__construct($_db);
			
			$this->class_name = 'analytic';
			$this->table = 'analytics';
			$this->db = $_db;
			
		}
		
	}

?>
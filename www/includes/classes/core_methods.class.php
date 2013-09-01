<?php

	
	

	abstract class core_methods extends core
	{
	
		public $class_name;
		public $table;
		public $db;
		
		function __construct($_db)
		{
			
			parent::__construct($_db);
			
		}
		
		function get_primaries($_where = "", $_offset = 0, $_limit = 25, $_order_by = 'id')
		{
			
			$_object = new $this->class_name($this->db);
		
			//Ordering by date_created DESC to get the most recent submission
			$_primary_array = $this->db_read->retrieve_all_primaries($this->table, $_object->db_variables, $_where, $_offset, $_limit, 'ORDER BY ' . $_order_by);
			
			return $_primary_array;
		
		}
	
		function get_paginated_primaries($_pagination, $_where = "", $_order_by = 'id')
		{
			
			$_object = new $this->class_name($this->db);
		
			//Ordering by user_id DESC to get the most recent submission
			$_primary_array = $this->db_read->retrieve_all_primaries($this->table, $_object->db_variables, $_where, $_pagination->get_offset(), $_pagination->get_limit(), 'ORDER BY ' . $_order_by);
			
			return $_primary_array;
		
		}
		
		function get_distinct_primaries($distinct_key, $_where = "", $_offset = 0, $_limit = 25, $_order_by = 'id')
		{
			
			$_object = new $this->class_name($this->db);
		
			//Ordering by date_created DESC to get the most recent submission
			$_primary_array = $this->db_read->retrieve_distinct_primaries($this->table, $distinct_key, $_where, $_offset, $_limit, 'ORDER BY ' . $_order_by);
			
			return $_primary_array;
		
		}
		
		function get_assoc_array_for_field_name($_field_name, $_order_by = 'id', $_where = '')
		{
			
			$_object = new $this->class_name($this->db);
		
			//Ordering by date_created DESC to get the most recent submission
			$_primary_array = $this->db_read->retrieve_all_primaries($this->table, $_object->db_variables, $_where, 0, $this->get_count(), 'ORDER BY ' . $_order_by);
			
			$_result_set = array();
			
			foreach($_primary_array as $_primary)
			{
			
				$_object->load_by_primary($_primary);
				
				$_result_set[$_object->get_value('id')] = $_object->get_value($_field_name);
				
			}
			
			return $_result_set;
			
		}
		
		function get_count($_where = '')
		{
		
			$_object = new $this->class_name($this->db);
			
			return $this->db_read->count_objects($this->table, $_object->db_variables, $_where);
			
		}

	}

?>
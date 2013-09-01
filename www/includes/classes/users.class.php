<?php
	
	
	

	class users extends core_methods
	{
	
		function __construct($_db)
		{
		
			parent::__construct($_db);
			
			$this->class_name = 'user';
			$this->table = 'users';
			$this->db = $_db;
			
		}
		
		function get_assoc_array_for_field_name($_field_name, $_order_by = 'id', $_where = '')
		{
			
			$_object = new $this->class_name($this->db);
		
			//Ordering by date_created DESC to get the most recent submission
			$_primary_array = $this->db_read->retrieve_all_primaries($this->table, $_object->db_variables, $_where, 0, $this->get_count(), 'ORDER BY ' . $_order_by);
			
			foreach($_primary_array as $_primary)
			{
			
				$_object->load_by_primary($_primary);
				
				if(is_array($_field_name))
				{
								
					foreach($_field_name as $value)
					{
						
						$values[] = $_object->get_value($value);
						
					}
					
					$_result_set[$_object->get_value('id')] = implode(' ', $values);
					
					unset($values);
					
				}
				else
				{
					
					$_result_set[$_object->get_value('id')] = $_object->get_value($_field_name);
					
				}
				
			}
			
			return $_result_set;
			
		}
		
	}

?>
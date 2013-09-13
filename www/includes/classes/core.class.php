<?php

	
	

	abstract class core
	{

		protected $db_variables;
		public $db_read;
		public $db_write;
		protected $table;
		
		private $has_changed;

		function __construct($_db)
		{
		
			if(get_class($_db) == 'db')
			{
				
/*
				$this->db_read = $_db->get_read_connection();
				$this->db_write = $_db->get_write_connection();
*/
				$this->db_read = $_db;
				$this->db_write = $_db;
				
			}
			else
			{
				
				$this->db_read = false;
				$this->db_write = false;
				
			}
			
			$this->has_changed = false;
		
		}

		function __destruct()
		{
		
			unset($this->db_read);
			unset($this->db_write);
			unset($this->has_changed);
		
		}
	
		//This function is for reading or writing to the object -- not the database
		function data($_var, $_new_data = null)
		{
		
			if(is_null($_new_data))
			{
				
				//this is a read!
				if($this->db_variables[$_var]['accessible'] & DB::DB_READ)
				{
				
					//allowed to be read?
					//return the data!
					return $this->db_variables[$_var]['data'];
				
				}
				else
				{
				
					errors::log_to_file("$_var is not allowed to be read");
					
					return false;
				
				}
			
			}
			else
			{
			
				//this is a write!
				if($this->db_variables[$_var]['accessible'] & DB::DB_WRITE)
				{
				
					//allowed to be written?
					$_data = $this->validate_data($this->db_variables[$_var]['type'], $_new_data);//run validation
					
					if($_data != FALSE || $this->db_variables[$_var]['type'] == DB::DB_BOOL || $this->db_variables[$_var]['type'] == DB::DB_INT)
					{
					
						//did we pass validation?
						if($this->db_variables[$_var]['data'] != $_data)
						{
						
							//data has indeed changed
							if($this->db_variables[$_var]['encrypt'] & DB::DB_MD5)
							{
							
								//we need to MD5 before saving
								$_data = md5($_data . SALT);
							
							}
						
							$this->db_variables[$_var]['data'] = $_data;
							$this->has_changed = TRUE;
						
						}
					
						return true;
					
					}
					else
					{
					
						errors::log_to_file($_var . ' failed validation for: ' . get_class($this) . '. Invalid data: ' . $_new_data);
						
						return false;
					
					}
				}
				else
				{
				
					errors::log_to_file($_var . ' is not allowed to be written for: ' . get_class($this));
					
					return false;
				
				}
			
			}
		
		}
		
		function validate_data($_type,$_data)
		{
		
			switch($_type)
			{
			
				case DB::DB_INT:
				
					if(empty($_data))
					{
					
						$data = DB::DEFAULT_INT;
						
					}
					else
					{
					
						$data = filter_var(filter_var($_data,FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);//validate data based on type
						
					}
					
					break;
					
				case DB::DB_FLOAT:
				
					if(empty($_data))
					{
					
						$data = DB::DEFAULT_FLOAT;
						
					}
					else
					{
					
						$data = filter_var(filter_var($_data,FILTER_SANITIZE_NUMBER_FLOAT),FILTER_VALIDATE_FLOAT);//validate data based on type
						
					}
					
					break;
					
				case DB::DB_DATE:
				
					if($_data == DB::DEFAULT_DATE || empty($_data))
					{
					
						$data = DB::DEFAULT_DATE;
						
					}
					else
					{
					
						$datearray = explode('-',$_data);
						
						if(checkdate($datearray[1],$datearray[2],$datearray[0]))
						{
						
							$data = $_data;
							
						}
						else
						{
						
							$data = FALSE;
							
						}
						
					}
					
					break;
					
				case DB::DB_TIME:
				
					if($_data == DB::DEFAULT_TIME || empty($_data))
					{
					
						$data = DB::DEFAULT_TIME;
						
					}
					else
					{
					
						$timearray = explode(':',$_data);
						
						$valid = FALSE;
						
						if(count($timearray)==2 || count($timearray)==3)
						
						{
						
							if(is_numeric($timearray[0]) && (int) $timearray[0] >= 0 && (int) $timearray[0]<24)
							
							{
							
								if(is_numeric($timearray[1]) && (int) $timearray[1] >= 0 && (int) $timearray[1]<60)
								{
								
									if(isset($timearray[2]))
									{
									
										if(is_numeric($timearray[2]) && (int) $timearray[2] >= 0 && (int) $timearray[2]<60)
										{
										
											$valid = TRUE;
											
										}
										
									}
									else
									{
									
										$_data .= ":00";
										$valid = TRUE;
										
									}
									
								}
								
							}
							
						}
						
						if($valid)
						{
						
							$data = $_data;
							
						}
						else
						{
						
							$data = FALSE;
							
						}
					}
					
					break;
					
				case DB::DB_DATETIME:
				
					if($_data == DB::DEFAULT_DATETIME || empty($_data))
					{
					
						$data = DB::DEFAULT_DATETIME;
						
					}
					else
					{
					
						$valid = FALSE;
						
						$datetimearray = explode(' ',$_data);
						
						if(isset($datetimearray[0]))
						{
						
							$datearray = explode('-',$datetimearray[0]);
							
							if(checkdate($datearray[1],$datearray[2],$datearray[0]))
							{
							
								if(isset($datetimearray[1]))
								{
								
									$timearray = explode(':',$datetimearray[1]);
									
									if(count($timearray)==2 || count($timearray)==3)
									{
									
										if(is_numeric($timearray[0]) && (int) $timearray[0] >= 0 && (int) $timearray[0]<24)
										{
										
											if(is_numeric($timearray[1]) && (int) $timearray[1] >= 0 && (int) $timearray[1]<60)
											{
											
												if(isset($timearray[2]))
												{
												
													if(is_numeric($timearray[2]) && (int) $timearray[2] >= 0 && (int) $timearray[2]<60)
													{
													
														$valid = TRUE;
														
													}
													
												}
												else
												{
												
													$_data .= ":00";
													$valid = TRUE;
													
												}
												
											}
											
										}
										
									}
									
								}
								else
								{
								
									$_data .= " 00:00:00";
									$valid = TRUE;
									
								}
								
							}
							
						}					
						if($valid)
						{
						
							$data = $_data;
							
						}
						else
						{
						
							$data = FALSE;
							
						}
					}
					
					break;
					
				case DB::DB_STR:
				
					if(empty($_data))
					{
					
						$data = DB::DEFAULT_STR;
						
					}
					else
					{
					
						$data = filter_var($_data,FILTER_SANITIZE_STRING);
						
					}
					
					break;
					
				case DB::DB_BOOL:
				
					if(empty($_data))
					{
					
						$data = DB::DEFAULT_BOOL;
						
					}
					else
					{
					
						$data = filter_var($_data,FILTER_VALIDATE_BOOLEAN);
						
					}
					break;
					
				case DB::DB_EMAIL:
				
					if(empty($_data))
					{
					
						$data = DB::DEFAULT_STR;
						
					}
					else
					{
					
						$data = filter_var(filter_var($_data,FILTER_SANITIZE_EMAIL),FILTER_VALIDATE_EMAIL);
						
					}
					
					break;
					
				case DB::DB_URL:
				
					if(empty($_data))
					{
					
						$data = DB::DEFAULT_STR;
						
					}
					else
					{
					
						$data = filter_var(filter_var($_data,FILTER_SANITIZE_URL),FILTER_VALIDATE_URL); 
						
					}
					
					break;
					
				default:
				
					error_log("Variable could not be validated, missing type.");
					$data = FALSE;
					
			}
			
			return $data;
			
		}
	
		function load()
		{
		
			//assumes primary is set
			$_result = $this->db_read->retrieve_one($this->table, $this->db_variables);
		
			if(!$_result)
			{
			
				errors::log_to_file("load failed");
				
				return false;
			
			}
			else
			{
			
				$this->has_changed = FALSE;
				
				return true;
			
			}
		
		}
	
		function load_by_primary($_primary)
		{
		
			foreach($this->db_variables as $_name => $_data_array)
			{
			
				if($_data_array['primary_key'])
				{
				
					$_data = $this->validate_data($_data_array['type'], $_primary);//run validation
				
					if(!$_data)
					{
					
						errors::log_to_file('load_by_primary failed data validation for: ' . get_class($this));
						
						return false;
					
					}
				
					$this->db_variables[$_name]['data'] = $_data;
					
					return $this->load();
				
				}
			
			}
		
		}
		
		function delete_by_primary($_primary)
		{
		
			foreach($this->db_variables as $_name => $_data_array)
			{
			
				if($_data_array['primary_key'])
				{
				
					$_data = $this->validate_data($_data_array['type'], $_primary);//run validation
				
					if(!$_data)
					{
					
						errors::log_to_file('delete_by_primary failed data validation for: ' . get_class($this));
						
						return false;
					
					}
				
					$this->db_variables[$_name]['data'] = $_data;
					
					return $this->db_read->delete_object($this->table, $this->db_variables);
				
				}
			
			}
		
		}
	
		function save()
		{
		
			if($this->has_changed && $this->db_write !== false)
			{
			
				$_result = $this->db_write->store($this->table, $this->db_variables);
				
				if(!$_result)
				{
				
					errors::log_to_file("write failed");
					
					return 0;
				
				}
				else
				{
				
					//we most likely got back an insert ID...let's update the object with the key data
					if($_result !== true)
					{
					
						foreach($this->db_variables as $_name => $_data_array)
						{
						
							if($_data_array['primary_key'])
							{
							
								$_data = $this->validate_data($_data_array['type'], $_result); //run validation on primary key
								
								if(!$_data)
								{
								
									errors::log_to_file('save failed validation of primary key on insert for:' . get_class($this));
									
								}
								else
								{
								
									$this->db_variables[$_name]['data'] = $_data;
									
								}
								
								break;
								
							}
							
						}
						
					}
				
					$this->has_changed = false;
					
					return $_result;
				
				}
			}
			else
			{
			
				return 0;
			
			}
		
		}
		
		//return object variable array
		function get_table()
		{
		
			 return $this->table;
		
		}
		
		//return object variable array
		function get_db_variables()
		{
		
			 return $this->db_variables;
		
		}
	
		//alias for data(read)
		function get_value($_var)
		{
		
			return $this->data($_var);
		
		}
	
		//alias for data(write)
		function put_value($_var, $_new_data)
		{
		
			return $this->data($_var, $_new_data);
		
		}
	
		//search for primary for field and value
		function find_primary($_field, $_data)
		{
		
			$_primary = $this->db_read->search($_field, $_data, $this->table, $this->db_variables);
		
			return $_primary;
		
		}
		
		function get_count()
		{
			
			return $this->db_read->retrieve_count($this->table, $this->db_variables);
			
		}
		
		//Dump it out
		function dump()
		{
		
			errors::log_to_file(var_export($this->db_variables, TRUE));
			
		}
	
	}

?>
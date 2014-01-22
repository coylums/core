<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

		
	$link = mysql_connect('localhost', 'wwoolard', 'filters1!');
	
	$database = 'test_core';

	mysql_select_db($database, $link);

	//get all of the tables
	$tables = array();
	
	$result = mysql_query('SHOW TABLES');
	
	while($row = mysql_fetch_row($result))
	{
	
		$tables[] = $row[0];
		
	}
	
	//cycle through
	foreach($tables as $table)
	{
	
		$result = mysql_query("SELECT column_name, data_type, character_maximum_length, column_key FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $database . "' AND TABLE_NAME = '" . $table . "'");
		
		$class_text  = "<?php\n\n";
		
		$singular_table = str_replace('ies', 'y', $table);
		
		if(substr($table, -1) == 's')
		{
			
			$singular_table = substr($table, 0, -1);
			
		}
		
		$class_text .= "\t" . "class " . $singular_table . " extends \core\n";
		$class_text .= "\t" . "{\n\n";
		$class_text .= "\t\t" . 'function __construct($_db, $_primary_id = 0)' . "\n";
		$class_text .= "\t\t" . "{\n\n";
		$class_text .= "\t\t\t" . 'parent::__construct($_db);' . "\n\n";
		$class_text .= "\t\t\t" . '$this->table = \'' . $table . '\';' . "\n\n";
		$class_text .= "\t\t\t" . '$this->db_variables = array(' . "\n\n";
		
		$class_text_array = array();
		
		$class_column_name_text = '';
		
		while($row = mysql_fetch_assoc($result))
		{
		
			$class_column_name_text .= "\t\t\t\t" . "'" . $row['column_name'] . "' 		=> array(" . "\n";
			
			switch($row['data_type'])
			{
			
				case 'int' :
				case 'tinyint' :
				
					$class_column_name_text .= "\t\t\t\t\t" . "'data' => \core\DB::DEFAULT_INT," . "\n";
					$class_column_name_text .= "\t\t\t\t\t" . "'type' => \core\DB::DB_INT," . "\n";
					
					break;
					
				case 'varchar' :
				
					$class_column_name_text .= "\t\t\t\t\t" . "'data' => \core\DB::DEFAULT_STR," . "\n";
					$class_column_name_text .= "\t\t\t\t\t" . "'type' => \core\DB::DB_STR," . "\n";
					
					break;
					
				case 'datetime' :
				
					$class_column_name_text .= "\t\t\t\t\t" . "'data' => \core\DB::DEFAULT_DATE," . "\n";
					$class_column_name_text .= "\t\t\t\t\t" . "'type' => \core\DB::DB_DATE," . "\n";
					
					break;
			
			}

			$class_column_name_text .= "\t\t\t\t\t" . "'encrypt' => FALSE," . "\n";
			
			switch($row['column_name'])
			{
			
				case 'date_created':
				case 'date_updated':
				case 'date_disabled':
				case 'date_deleted':
				case 'id':
				
					$class_column_name_text .= "\t\t\t\t\t" . "'accessible' => \core\DB::DB_READ," . "\n";
					
					break;
					
				default;
				
					$class_column_name_text .= "\t\t\t\t\t" . "'accessible' => \core\DB::DB_RW," . "\n";
					
					break;
			
			}
			
			if(isset($row['column_key']) && $row['column_key'] == 'PRI')
			{
			
				$class_column_name_text .= "\t\t\t\t\t" . "'primary_key' => TRUE" . "\n";
			
			}
			else
			{
			
				$class_column_name_text .= "\t\t\t\t\t" . "'primary_key' => FALSE" . "\n";
			
			}
			
			$class_column_name_text .= "\t\t\t\t" . ")";
			
			$class_text_array[] = $class_column_name_text;
			
			$class_column_name_text = '';
			
		}
		
		$class_text .= implode(",\n", $class_text_array);
		
		unset($class_text_array);
		

		$class_text .= "\n\n\t\t\t" . ');' . "\n\n";
		
		//finish up 
		$class_text .= "\t\t\t" . 'if($_primary_id != 0)' . "\n";
		$class_text .= "\t\t\t" . '{' . "\n\n";
		$class_text .= "\t\t\t\t" . 'return $this->load_by_primary($_primary_id);' . "\n\n";
		$class_text .= "\t\t\t" . '}' . "\n\n";
		$class_text .= "\t\t" . '}' . "\n\n";
		
		//save function
		$class_text .= "\t\t" . 'function save()' . "\n";
		$class_text .= "\t\t" . '{' . "\n\n";
		$class_text .= "\t\t\t" . "if(empty(" . '$this' . "->db_variables['id']['data']))" . "\n";
		$class_text .= "\t\t\t" . "{" . "\n\n";
		$class_text .= "\t\t\t\t" . '$this' . "->db_variables['date_created']['data'] = date('Y-m-d H:i:s');" . "\n\n";
		$class_text .= "\t\t\t" . "}" . "\n\n";
		$class_text .= "\t\t\t" . '$this' . "->db_variables['date_updated']['data'] = date('Y-m-d H:i:s');" . "\n\n";
		$class_text .= "\t\t\t" . 'return parent::save();' . "\n\n";
		$class_text .= "\t\t" . "}" . "\n\n";
		
		$class_text .= "\t" . "}";
	
		//save file
		$handle = fopen('model_classes/' . $table . '.class.php','w');
		
		fwrite($handle, $class_text);
		
		fclose($handle);
		
		$class_text = '';
		
	}
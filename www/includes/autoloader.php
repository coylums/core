<?php

	//nullify any existing autoloads
    spl_autoload_register(null, false);

    //specify extensions that may be loaded
    spl_autoload_extensions('.php, .class.php');

    //class Loader
    function class_loader($class)
    {
    
        $filename = strtolower($class) . '.class.php';
        
        $file_at_path = CLASS_DIRECTORY . $filename;
        
        if(!file_exists($file_at_path))
        {
        
	        $filename = strtolower($class) . '.php';
	        
	        $file_at_path = CLASS_DIRECTORY . $filename;
	        
	        if(!file_exists($file_at_path))
	        {
	        
	            return false;
	            
	        }
            
        }
        
        include $file_at_path;
        
    }

    //register the loader functions
    spl_autoload_register('class_loader');

?>
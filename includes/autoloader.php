<?php

	//nullify any existing autoloads
    spl_autoload_register(null, false);

    //specify extensions that may be loaded
    spl_autoload_extensions('.class.php');

    //class Loader
    function class_loader($class)
    {

		$parts = explode('\\', $class);
    
        $filename = end($parts) . '.class.php';
        
        $file_at_path = 'source/model_classes/' . $filename;
        
        include $file_at_path;
        
    }

    //register the loader functions
    spl_autoload_register('class_loader');
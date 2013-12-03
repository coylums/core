<?php

	namespace core;

	class config extends \Pimple
	{
	
	    public function __construct()
	    {
	    
	        $this['parameter'] = 'foo';
	        
			$this['host_name'] = function ()
			{
			
				return $_SERVER['HTTP_HOST'];
			
			};
			
			$this['domain_name'] = function ()
			{
				
				$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
				
				$url = $protocol . '://' . get_current_host_name();
				
				return $url;
			
			};
			
			$this['url'] = function ()
			{
				
				return get_current_domain() . $_SERVER['PHP_SELF'];
			
			};
	        
	        $this['object'] = function () { return stdClass(); };

	    }
	    
	}
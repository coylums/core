<?php

	namespace core;

	class config extends \Pimple
	{
	
	    public function __construct()
	    {
	        
			$this['host_name'] = function ()
			{
			
				return $_SERVER['HTTP_HOST'];
			
			};
			
			$this['domain_name'] = function ()
			{
				
				$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
				
				$url = $protocol . '://' . $this['host_name'] . '/';
				
				return $url;
			
			};
			
			$this['url'] = function ()
			{
				
				return $this['domain_name'] . $_SERVER['PHP_SELF'];
			
			};

			$this['root_url'] = function () {

				return $this['domain_name'] . dirname($_SERVER['PHP_SELF']) . '/';

			};
	        
	        $this['object'] = function () { return stdClass(); };

	    }
	    
	}
<?php

	namespace core;

	class config extends \Pimple
	{
	
	    public function __construct()
	    {
	        
	    	//TODO remove $this/$that hack when on HP 5.4+
	    	$that = $this;
	        
			$this['host_name'] = function ()
			{
			
				return $_SERVER['HTTP_HOST'];
			
			};
			
			$this['domain_name'] = function () use ($that)
			{
				
				$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
				
				$url = $protocol . '://' . $that['host_name'] . '/';
				
				return $url;
			
			};
			
			$this['url'] = function () use ($that)
			{
				
				return $that['domain_name'] . $_SERVER['PHP_SELF'];
			
			};

			$this['root_url'] = function () use ($that)
			{

				return $that['domain_name'] . trim(dirname($_SERVER['PHP_SELF']), '/') . '/';

			};
	        
	        $this['object'] = function () { return stdClass(); };

	    }
	    
	}
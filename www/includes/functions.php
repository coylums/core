<?php
	
	function get_current_host_name()
	{
	
		return $_SERVER['HTTP_HOST'];
	
	}
	
	function get_current_domain()
	{
		
		$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
		
		$url = $protocol . '://' . get_current_host_name();
		
		return $url;
	
	}
	
	function get_current_url()
	{
		
		return get_current_domain() . $_SERVER['PHP_SELF'];
	
	}
	
	function get_months_for_date_range($_start_date, $_end_date)
	{
		
		$start_date = new DateTime($_start_date);
		$end_date = new DateTime($_end_date);
		
		$increment = DateInterval::createFromDateString('first day of next month');
		
		//modify end_date
		$end_date->modify('+1 day');
		
		$period = new DatePeriod($start_date, $increment, $end_date);
		
		$months = array();
		
		foreach ($period as $month)
		{
	
			$months[$month->format('F') . " '" . $month->format('y')] = $month->format('n');
			
		}
		
		return $months;
		
	}
	
?>
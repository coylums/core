<?php

	//optional

	namespace core;

	class pagination extends core
	{
	
		private $paginate;
		private $target_page;
		private $object_count;
		private $total_pages;
		private $page_limit;

		function __construct($_db, $_object, $_page, $_limit = 25, $_where = "")
		{
		
			parent::__construct($_db);
			
			$_new_object = new $_object($_db);
				
			$this->object_count = $this->db_read->count_objects($_new_object->table, $_new_object->db_variables, $_where);
			
			$this->total_pages = $this->object_count;
			
			$this->page = (isset($_page)) ? $_page : 0;
			
			$this->limit = $_limit;
			
			$this->target_page = $_db->db_config['domain_name'] . "?"; //get_current_url is in ../functions.php
		
		}
		
		function get_limit()
		{
		
			return $this->limit;
		
		}
		
		function get_offset()
		{
		
			if($this->limit * ($this->page - 1) > 0)
			{
			
				return $this->limit * ($this->page - 1);
			
			}
			else
			{
			
				return 0;
			
			}
		
		}
		
		function get_page()
		{
		
			return $this->page;
		
		}
		
		function display_pagination()
		{
	
			$target_page = $this->target_page;
			
			$_page = $this->page;
			
			$_limit = $this->limit;
			
			$total_pages = $this->total_pages;
			
			$_stages = 3;
			
			if($_page)
			{
			
				$start = ($_page - 1) * $_limit;
				
			}
			else
			{
			
				$start = 0;
				
			}
			
			// Initial page num setup
			if ($_page == 0)
			{
			
				$_page = 1;
				
			}
			
			$_previous = $_page - 1;	
			$_next = $_page + 1;
								
			$_last_page = ceil($total_pages/$_limit);
			$_last_page_minus_1 = $_last_page - 1;			
			
			$paginate = '';
			
			if($_last_page > 1)
			{	
		
				$paginate .= "<div class='paginate'>";
				
				// Previous
				if ($_page > 1)
				{
				
					$paginate.= "<a href='$target_page" . "" . "page=$_previous'>previous</a>";
					
				}
				else
				{
				
					$paginate.= "<span class='disabled'>previous</span>";
					
				}
					
				// Pages	
				if ($_last_page < 7 + ($_stages * 2))	// Not enough pages to breaking it up
				{	
					for ($_counter = 1; $_counter <= $_last_page; $_counter++)
					{
					
						if ($_counter == $_page)
						{
						
							$paginate.= "<span class='current'>$_counter</span>";
							
						}
						else
						{
						
							$paginate.= "<a href='$target_page" . "" . "page=$_counter'>$_counter</a>";
							
						}
											
					}
					
				}
				else if($_last_page > 5 + ($_stages * 2))	// Enough pages to hide a few?
				{
					// Beginning only hide later pages
					if($_page < 1 + ($_stages * 2))		
					{
						for ($_counter = 1; $_counter < 4 + ($_stages * 2); $_counter++)
						{
							if ($_counter == $_page)
							{
							
								$paginate.= "<span class='current'>$_counter</span>";
								
							}
							else
							{
							
								$paginate.= "<a href='$target_page" . "" . "page=$_counter'>$_counter</a>";
							
							}
							
						}
						
						$paginate.= "...";
						$paginate.= "<a href='$target_page" . "" . "page=$_last_page_minus_1'>$_last_page_minus_1</a>";
						$paginate.= "<a href='$target_page" . "" . "page=$_last_page'>$_last_page</a>";
							
					}
					// Middle hide some front and some back
					elseif($_last_page - ($_stages * 2) > $_page && $_page > ($_stages * 2))
					{
					
						$paginate.= "<a href='$target_page" . "" . "page=1'>1</a>";
						$paginate.= "<a href='$target_page" . "" . "page=2'>2</a>";
						$paginate.= "...";
						
						for ($_counter = $_page - $_stages; $_counter <= $_page + $_stages; $_counter++)
						{
						
							if ($_counter == $_page)
							{
							
								$paginate.= "<span class='current'>$_counter</span>";
								
							}
							else
							{
							
								$paginate.= "<a href='$target_page" . "" . "page=$_counter'>$_counter</a>";
							
							}
							
						}
						
						$paginate.= "...";
						$paginate.= "<a href='$target_page" . "" . "page=$_last_page_minus_1'>$_last_page_minus_1</a>";
						$paginate.= "<a href='$target_page" . "" . "page=$_last_page'>$_last_page</a>";	
							
					}
					// End only hide early pages
					else
					{
					
						$paginate.= "<a href='$target_page" . "" . "page=1'>1</a>";
						$paginate.= "<a href='$target_page" . "" . "page=2'>2</a>";
						$paginate.= "...";
						
						for ($_counter = $_last_page - (2 + ($_stages * 2)); $_counter <= $_last_page; $_counter++)
						{
						
							if ($_counter == $_page)
							{
							
								$paginate.= "<span class='current'>$_counter</span>";
								
							}
							else
							{
							
								$paginate.= "<a href='$target_page" . "" . "page=$_counter'>$_counter</a>";
							
							}
							
						}
						
					}
					
				}
							
				// Next
				if ($_page < $_counter - 1)
				{ 
				
					$paginate.= "<a href='$target_page" . "" . "page=$_next'>next</a>";
					
				}
				else
				{
				
					$paginate.= "<span class='disabled'>next</span>";
					
				}
					
				$paginate.= "</div>";
			
			}
			
			echo "<h4 class=\"total_results\">" . $total_pages . " Results </h4>";
			
			// pagination
			echo $paginate;
		
		}
	
	}

?>

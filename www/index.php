<?php

	require_once 'includes/classes/config.class.php';

	$config = new config();
	
	$db = new db($config);

	require_once 'includes/authenticate.php';

	if(isset($_SESSION['user_id']))
	{
		
		$user = new user($db, $_SESSION['user_id']);
		
	}
	
	$title = "Home";
	
	$active = "home";
	
	require_once "includes/header.php";
	
?>
			
			<div id="content">
							

				
			</div><!-- end content -->
				
<?php require_once "includes/footer.php"; ?>
			

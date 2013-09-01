<?php

	//Optional
	
	require_once "classes/users.class.php";
	require_once "classes/analytics.class.php";
	
	$_SESSION['message'] = "";
	
	if(isset($_POST['logout']))
	{
	
		unset($_SESSION['user_id']);
		
	}
	
	if(isset($_POST['admin']))
	{
	
		header("Location: " . HOST_NAME . "admin/index.php");
		
	}
	
	if(!isset($_SESSION['user_id']))
	{
	
		//not logged in
		//check for submitted login
		if(isset($_POST['submit_login']))
		{
		
			//process login info
			$user = new user($db);
			
			$id = $user->login($_POST['email'], $_POST['password']);
			
			if(!empty($id))
			{
			
				//successful login!
				$_SESSION['user_id'] = $id;
				
				$user->load_by_primary($id);
				
				$analytic = new analytic($db);
				
				$analytic->put_value("user_id", $user->get_value("id"));
				$analytic->put_value("ip_address", $_SERVER["REMOTE_ADDR"]);
				
				$analytic->save();
				
				header("Location: index.php");
				
			}
			else
			{
			
				//failed login!
				$_SESSION['message'] = " Invalid username or password.";
				
			}
			
		}
		if(!isset($_SESSION['user_id']) && @$request_page != true)
		{
			
			$title = 'Sign In';
			
			$sub_title = $title;
			
			$active = 'home';
			
			require_once "header.php";
			
?>

			<div id="content" class="admin">
			
				<div id="content_inner_container">			
			
					<div id="sign_in_container">
					
						<h3>Sign In</h3>
				
						<form action="" method="post" id="sign_in_form">
						
							<p><label for="email">Email:</label><br /><input id="email" class="textfield" type="text" value="" name="email" /></p>
							<p><label for="password">Password:</label><br /><input id="password" class="textfield" type="password" value="" name="password" /></p>
							<p class="align_left"><input id="submit" type="submit" value="Sign in" name="submit_login" /></p>
						
						</form><!-- end sign_in_form-->
					
					</div><!-- end sign_in_container -->
				
				</div><!-- end content_inner_container -->
			
			</div><!-- end content -->

<?php

			require_once "footer.php";
			
			exit;
			
		}
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
			<title><?php echo $title; ?> | WEBSITE TITLE</title>

		<link rel="apple-touch-icon" href="<?php echo DOMAIN_NAME; ?>images/icon.png" />
		<link rel="shortcut icon" type="image/favicon" href="<?php echo DOMAIN_NAME; ?>favicon.ico" />
		
		<link type="text/css" media="screen" rel="stylesheet" href="<?php echo DOMAIN_NAME; ?>styles/reset.css"/>
		<link type="text/css" media="screen" rel="stylesheet" href="<?php echo DOMAIN_NAME; ?>styles/styles.css"/>
		
		<!--[if IE 6]>
			<link rel="stylesheet" type="text/css" href="styles/ie6.css" />
		<![endif]-->
		
		<!--[if IE 7]>
			<link rel="stylesheet" type="text/css" href="styles/ie7.css" />
		<![endif]-->
		
		<!--[if IE 8]>
			<link rel="stylesheet" type="text/css" href="styles/ie8.css" />
		<![endif]-->
		
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo DOMAIN_NAME; ?>js/scripts.js"></script>
		
	</head>
	<body>
	
		<div id="wrapper">
		
			<div id="header_wrapper">
		
				<div id="header">
				
					<?php if(isset($_SESSION['user_id'])) { ?>
				
					<form method="post" action="">
					
						<p id="logout_button"><input type="submit" name="logout" value="Logout" /></p>
						
					</form>
					
					<?php } ?>
				
					<h1 id="title">WEBSITE TITILE</h1>
					<h2 id="subtitle">WEBSITE SUBTITLE</h2>
				
				</div>
				
			</div>
<?php
	session_start();
?>

<!DOCTYPE html PUBLIC>
<html xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="Description" content="My first internet forum." />
	<meta name="keywords" content="forum, ronny, macmaster" />
	<title>Macmaster forum</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
	<h1>Welcome to the Skull Forum</h1>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="index.php">Home</a>
		<a class="item" href="create_topic.php">Create a topic</a>
		<?php // only admins can create categories
			if(isset($_SESSION['user_level']) && $_SESSION['user_level'] == 1){
				echo '<a class="item" href="create_category.php">Create a category</a>';
			}
		?>
		
		<div id="userbar">
			<?php
				if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true){
					echo "Hello " . $_SESSION['user_name'] . ". Not you? "; 
					echo '<a class="item" href="logout.php">Log out</a>';
				} else{ // user not signed in.
					echo '<a class="item" href="login.php">Sign In</a>';
					echo ' or <a class="item" href="register.php">Create an Account</a>';
				}
			?>
		</div>
	</div>
	<div id="content">
	
	<h2>Our first ever web forum...</h2>
	

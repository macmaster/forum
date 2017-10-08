<?php
	session_start();
?>

<!DOCTYPE html PUBLIC>
<html xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="Description" content="My first internet forum." />
	<meta name="keywords" content="forum, ronny, macmaster" />
	<title>Knock GQ</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
	<h1><a href="/" style="color:white;">Knock GQ</a></h1>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="/">Home</a>
		<a class="item" href="create_post.php">Create Post</a>

		<div id="userbar">
			<?php
				if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
					echo "Hello " . $_SESSION['user_name']; 
					echo '<a class="item" href="profile.php?id='.$_SESSION['user_id'].'">Profile</a>';
					echo '<a class="item" href="logout.php">Log out</a>';
				} else { // user not signed in.
					echo '<a class="item" href="login.php">Sign In</a>';
					echo ' or <a class="item" href="register.php">Create an Account</a>';
				}
			?>
		</div>
	</div>
	<div id="content">

	<h2>The Worst Place on the Internet</h2>


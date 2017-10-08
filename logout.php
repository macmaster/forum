<?php
	include "connect.php";
	include "header.php";

	// Check if user is already signed in...
	if(!isset($_SESSION['signed_in']) || $_SESSION['signed_in'] == false){
		echo 'You are not signed in yet. <br>';
		echo 'You can <a href="login.php">sign in</a> if you want to.';
	} 
	else{ // log the user out
		$_SESSION['signed_in'] = false;
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_level']);
		echo 'You are now signed out. <br>';
		echo 'Return to the <a href=index.php>homepage.</a>';
		echo "<script>setTimeout(\"location.href = 'index.php';\", 0);</script>"; // javascript redirect 3s
	}
	
	// page footer
	include "footer.php";
?>

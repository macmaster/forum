<?php
	include "connect.php";
	include "header.php";
	
	// registration form
	function print_form($username="", $password="", $email="") {
		echo '
			<div class="center-frame">
			<h3>Registration</h3>
			<form method="post" action="">
				Username: <input type="text" name="user_name" value="'.$username.'"/><br>
				Password: <input type="password" name="user_pass" value="'.$password.'"/><br>
				Password again: <input type="password" name="user_check_pass" /><br>
				E-mail (optional): <input type="email" name="user_email" value="'.$email.'"/><br>
				<input type="submit" value="Register">
			</form></div>';
	}
	
	// returns true is username already exists
	function user_exists($username) {
		global $mysqli;
		$result = $mysqli->query("SELECT 1 FROM users WHERE user_name = '$username'");
		if ($result && $result->num_rows > 0) {
			return true; // username already exists
		} else {
			return false;
		}
	}
	
	// Check if user is already signed in...
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
		echo 'You are already signed in. <br>';
		echo 'You can <a href="logout.php">sign out</a> if you want to.';
	} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		// Form hasn't been posted yet. display it.
		print_form(); 
	} else { 		
		// Process the data in 3 steps:
		// 1. Check the data
		// 2. Let user refill incorrect fields (if necessary)
		// 3. Save the data
		$errors = array();
		unset($username); unset($password);
		
		// check username
		if (!isset($_POST['user_name']) || !($username = $_POST['user_name'])) {
			$errors[] = "Username must not be empty!";
		} else if (!ctype_alnum($username)) {
			$errors[] = "Username can only contain letters and digits.";
		} else if (strlen($username) > 30) {
			$errors[] = "Username cannot be longer than 30 characters.";
		} else if (user_exists($username)) {
			$errors[] = "Username already taken.";
		}
		
		// check password
		if (!isset($_POST['user_pass']) || !($password = $_POST['user_pass'])) {
			$errors[] = "Password must not be empty!";
		} else if ($password != $_POST['user_check_pass']) {
			$errors[] = "The two passwords did not match.";
		} 

		// check email
		if (($email = $_POST['user_email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Email format is bad!";
		}

		// check for errors
		if (!empty($errors)) {
			echo "Uh-oh.. a couple of the fields are not filled in correctly..";
			echo "<ul>";
			foreach($errors as $key => $value){
				echo "<li>$value</li>";
			}
			echo "</ul>";
			print_form($username, $password, $email);
		} else{ 
			// Save registration the data
			$sql = "INSERT INTO users(user_name, user_pass, user_email, user_date, user_level)
					VALUES('" . $mysqli->real_escape_string($_POST['user_name']) ."',
						'" . sha1($_POST['user_pass']) . "',
						'" . $mysqli->real_escape_string($_POST['user_email']) . "', 
						NOW(), 0)";

			if (!($result = $mysqli->query($sql))) { 
				// something went wrong with the database
				echo "Something went wrong while registering. Please try again later...";
				echo $mysqli->error;
			} else {
				echo "Sucessfully registered. Now you can <a href=login.php>sign in</a> and start posting.";
				echo "<script>setTimeout(\"location.href = 'index.php';\", 0);</script>"; // javascript redirect 2s
			}
		}
	}
	
	// page footer
	include "footer.php";
?>

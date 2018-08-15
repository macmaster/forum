<?php
	include "connect.php";
	include "header.php";
	
	// registration form
	function print_form($username="", $password="") {
		echo '
			<div class="center-frame">
			<h3>Login</h3>
			<form id="reg_form" method="post" action="">
				Username: <input type="text" name="user_name" value="'.$username.'"/><br>
				Password: <input type="password" name="user_pass" value="'.$password.'"/><br>
			<div class="button-group">
				<button>Login</button>
				<button type="button" onclick="window.location=\'register.php\';">Create Account</button>
			</div>	
			</form></div>';
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
		if (!isset($_POST['user_name']) || $_POST['user_name'] == "") {
			$errors[] = "Username must not be empty!";
		}
		
		// check password
		if (!isset($_POST['user_pass']) || $_POST['user_pass'] == "") {
			$errors[] = "Password must not be empty!";
		}
		
		// check for errors
		if(!empty($errors)) {
			echo "Uh-oh... a couple of the fields are not filled in correctly...";
			echo "<ul>";
			foreach($errors as $key => $value) {
				echo "<li>$value</li>";
			}
			echo "</ul>";
			print_form($username, $password);
		} else { 
			// Check the validity of username and password
			$sql = "SELECT user_id, user_name, user_level FROM users
					WHERE user_name = '" . $mysqli->escape_string($_POST['user_name']) . "'
						AND user_pass = '" . sha1($_POST['user_pass']) . "'";
						
			if (!($result = $mysqli->query($sql))) {
				// something went wrong with the database
				echo "Something went wrong while registering. Please try again later...";
				echo $mysqli->error;
			} else if ($result->num_rows == 0) {
				// invalid username and password combination
				echo '<p>Your username/password is wrong. Please try again.</p>';
				print_form($_POST["user_name"], $_POST["user_pass"]);
			} else {
				// sucessful login
				$_SESSION['signed_in'] = true;
				$user = $result->fetch_assoc();
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['user_name'] = $user['user_name'];
				$_SESSION['user_level'] = $user['user_level'];
				echo "<script>setTimeout(\"location.href = '/';\", 0 * 1000);</script>"; // javascript redirect 2s
			}
		}
	}
	
	// page footer
	include "footer.php";
?>

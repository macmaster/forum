<?php
	include "connect.php";
	include "header.php";
	
	// registration form
	function print_form(){
		echo '
			<form method="post" action="">
			<h3>Registration</h3>
				Username: <input type="text" name="user_name" /><br>
				Password: <input type="password" name="user_pass" /><br>
				Password again: <input type="password" name="user_check_pass" /><br>
				E-mail: <input type="email" name="user_email" /><br>
				<input type="submit" value="Register">
			</form>';
	}
	
	// returns true is username already exists
	function user_exists($username){
		$result = mysql_query("SELECT 1 FROM users WHERE user_name = '$username'");
		if($result && mysql_num_rows($result) > 0){
			return true; // username already exists
		} else{
			return false;
		}
	}
	
	// Form hasn't been posted yet. display it.
	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		print_form();
	} 
	
	// Form has been posted 
	else { 
		/* Process the data in 3 steps:
		 1. Check the data
		 2. Let user refill incorrect fields (if necessary)
		 3. Save the data
		*/
		$errors = array();
		
		// check username
		if(isset($_POST['user_name'])){
			$username = $_POST['user_name'];
			if(!ctype_alnum($username)){
				$errors[] = "Username can only contain letters and digits.";
			}
			if(strlen($username) > 30){
				$errors[] = "Username cannot be longer than 30 characters.";
			}
			if(user_exists($username)){
				$errors[] = "Username already taken.";
			}
		} else{
			$errors[] = "Username must not be empty!";
		}
		
		// check password
		if(isset($_POST['user_pass'])){
			$password = $_POST['user_pass'];
			if($password != $_POST['user_check_pass']){
				$errors[] = "The two passwords did not match.";
			}
		} else{
			$errors[] = "Password must not be empty!";
		}
		
		// check for errors
		if(!empty($errors)){
			echo "Uh-oh.. a couple of the fields are not filled in correctly..";
			echo "<ul>";
			foreach($errors as $key => $value){
				echo "<li>$value</li>";
			}
			echo "</ul>";
			print_form();
		} else{ // Save registration the data
			$sql = "INSERT INTO
						users(user_name, user_pass, user_email, user_date, user_level)
					VALUES('" . mysql_real_escape_string($_POST['user_name']) ."',
						'" . sha1($_POST['user_pass']) . "',
						'" . mysql_real_escape_string($_POST['user_email']) . "', 
						NOW(), 0)";
						
			// execute query			
			$result = mysql_query($sql);
			if(!$result){
				// something went wrong with the database
				echo "Something went wrong while registering. Please try again later...";
				echo mysql_error();
			} else{
				echo "Sucessfully registered. Now you can <a href=login.php>sign in</a> and start posting.";
			}
		}
	}
	
	// page footer
	include "footer.php";
?>
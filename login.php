<?php
	include "connect.php";
	include "header.php";
	
	// registration form
	function print_form(){
		echo '
			<form method="post" action="">
			<h3>Sign in</h3>
				Username: <input type="text" name="user_name" /><br>
				Password: <input type="password" name="user_pass" /><br>
				<input type="submit" value="Login">
			</form>';
	}
	
	// Check if user is already signed in...
	if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true){
		echo 'You are already signed in. <br>';
		echo 'You can <a href="logout.php">sign out</a> if you want to.';
	} 
	else{
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
			if(!isset($_POST['user_name']) || $_POST['user_name'] == ""){
				$errors[] = "Username must not be empty!";
			}
			
			// check password
			if(!isset($_POST['user_pass']) || $_POST['user_pass'] == ""){
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
			} else{ // Check the validity of username and password
				$sql = "SELECT
							user_id, user_name, user_level
						FROM 
							users
						WHERE
							user_name = '" . mysql_escape_string($_POST['user_name']) . "'
						AND
							user_pass = '" . sha1($_POST['user_pass']) . "'";
							
				// execute query			
				$result = mysql_query($sql);
				if(!$result){
					// something went wrong with the database
					echo "Something went wrong while registering. Please try again later...";
					echo mysql_error();
				} else{
					// validate username and password combination
					if(mysql_num_rows($result) == 0){
						// invalid username and password combination
						echo '<p>Your username/password is wrong. Please try again.</p>';
						print_form();
					} else{
						// sucessful login
						$_SESSION['signed_in'] = true;
						$user = mysql_fetch_assoc($result);
						$_SESSION['user_id'] = $user['user_id'];
						$_SESSION['user_name'] = $user['user_name'];
						$_SESSION['user_level'] = $user['user_level'];
						echo "Welcome " . $_SESSION['user_name'] . "!<br> Proceed to the <a href=index.php>forum.</a>";
						echo "<script>setTimeout(\"location.href = 'index.php';\", 2 * 1000);</script>"; // javascript redirect 3s
					}
				}
			}
		}
	}
	
	// page footer
	include "footer.php";
?>
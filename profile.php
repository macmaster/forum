<?php
	include "connect.php";
	include "header.php";

	// topic form
	function print_form($email, $img) {
		echo '<div class="center-frame"><form method="post" action="">';
		echo "<h3>Update Profile</h3>";
		echo 'Email: <input type="email" name="user_email" value="'.$email.'"/>';
		echo 'Profile Picture URL: <input type="url" name="user_img" value="'.$img.'"/>';
		echo '<input type="submit" value="Submit"/></form></div>';
	}
	
	// Check if user is signed in
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$sql = "SELECT * FROM users 
			WHERE user_id = ".$_GET['id'].";"; 

		// update your profile		
		if (!($result = $mysqli->query($sql))) {
			// something wrong with fetching the profile.
			echo "Something went wrong while fetching the profile. Please try again later...";
			echo $mysqli->error;	
		} else if ($result->num_rows == 1) {
			$user = $result->fetch_assoc();
			echo '<h1 style="color:green;">'.$user['user_name'].'</h1>';
			echo '<img style="width:50%;height:50%" src="'.$user['user_img'].'">';
			if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id']) {
				print_form($user['user_email'], $user['user_img']); // Update Profile form
			}
		} else {
			echo '<p> user #'.$_GET['id'].' does not exist!!</p>';
		}
		 
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
		$sql = "UPDATE users SET 
			user_email = '".$_POST['user_email']."', 
			user_img = '".$_POST['user_img']."'
			WHERE user_id = ".$_SESSION['user_id'].";"; 

		// update your profile		
		if (!($result = $mysqli->query($sql))) {
			// something wrong with updating your profile.
			echo "Something went wrong while updating your profile. Please try again later...";
			echo $mysqli->error;
		} else {
			// profile was updated
			echo "<script>setTimeout(\"location.href = '';\", 0 * 1000);</script>";
		}
	}
	
	// page footer
	include "footer.php";
?>

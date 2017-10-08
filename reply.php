<?php
	include "connect.php";
	include "header.php";
	
	// prohibit calls through GET. forward to homepage
	if ($_SERVER['REQUEST_METHOD'] != "POST") {
		echo "<script>setTimeout(\"location.href = 'index.php';\", 0);</script>"; // javascript redirect 3s
	} else if (!isset($_SESSION['signed_in']) || $_SESSION['signed_in'] == false) {
			// Check if user is signed in
			echo 'You must be <a href="login.php">signed in</a> to post a reply. <br>';
			echo 'Return to the <a href=index.php>homepage.</a>';
			echo "<script>setTimeout(\"location.href = 'index.php';\", 3 * 1000);</script>"; // javascript redirect 3s
	} else {
		// create reply post
		$insert = "INSERT INTO 
			posts(post_content, post_date, post_topic, post_by) 
			VALUES ('" . $mysqli->escape_string($_POST['reply-content']) . "', NOW(), 
			" . $mysqli->escape_string($_GET['id']) . ", 
			" . $_SESSION['user_id'] . ");";

		
		if(!($mysqli->query($insert))) {
			// something went wrong with the database
			echo "Something went wrong while creating your reply. Please try again later...";
			echo $mysqli->error();
			echo "\n\n $sql";
		} else {
			// reply was created.
			echo 'Your reply has been saved. ';
			echo 'Check it out <a href="topic.php?id=' . $mysqli->escape_string($_GET['id']) . '">here.</a>';
			echo "<script>setTimeout(\"location.href = 'topic.php?id=" . $mysqli->escape_string($_GET['id']) . "';\", 0);</script>";
		}
	}
	
	// page footer
	include "footer.php";
?>

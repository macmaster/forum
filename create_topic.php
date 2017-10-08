<?php
	include "connect.php";
	include "header.php";

	// topic form
	function print_form($topic="",  $message=""){
		// retrieve category data
		$sql = "SELECT cat_id, cat_name, cat_description FROM categories";

		global $mysqli;
		if (!($result = $mysqli->query($sql))) {
			// something went wrong with the database
			echo "Something went wrong while fetching the category data. Please try again later...";
			echo $mysqli->error;
		} else if ($result->num_rows == 0) {
			echo "There are no categories yet. Please wait for an admin to create some.";
		} else {
			echo '<form method="post" action="">';
			echo "<h3>Create a Topic</h3>";
			echo 'Category: <select name="topic_cat">';
				while($row = $result->fetch_assoc()){
					echo '<option value="' . $row["cat_id"] . '">' . $row["cat_name"] . '</option>';
				}
			echo '</select> <br>';
			echo 'Topic Subject: <input type="text" name="topic_subject" value="'.$topic.'"/>';
			echo 'Message: <br><textarea name="post_content">'.$message.'</textarea>';
			echo '<input type="submit" value="Create Topic"/></form>';
		}
	}
	
	// Check if user is signed in
	if (!isset($_SESSION['signed_in']) || $_SESSION['signed_in'] == false) {
		echo 'You must be <a href="login.php">signed in</a> to create a toppic. <br>';
		echo 'Return to the <a href=index.php>homepage.</a>';
		echo "<script>setTimeout(\"location.href = 'index.php';\", 3 * 1000);</script>"; // javascript redirect 3s
	} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		print_form(); // Form hasn't been posted yet. display it.
	} else { 
		// start a transaction
		$errors = array();
		if (!($result = $mysqli->query("BEGIN WORK;"))) {
			echo 'An error occured while processing your topic transaction.';
			echo 'Please try again later.';
		} else {
			// check topic name
			if (!isset($_POST['topic_subject']) || !($topic = $_POST['topic_subject'])) {
				$errors[] = "Topic subject must not be empty!";
			}				
			// check topic category
			if (!isset($_POST['topic_cat']) || !($category = $_POST['topic_cat'])) {
				$errors[] = "Topic Category must not be empty!";
			}
			
			
			// check for errors
			if (!empty($errors)) {
				echo "Uh-oh.. a couple of the fields are not filled in correctly..";
				echo "<ul>";
				foreach($errors as $key => $value){
					echo "<li>$value</li>";
				}
				echo "</ul>";
				print_form($topic, $category);
			} else { 
				// Save topic subject and message
				$sql = "INSERT INTO topics(topic_subject, topic_date, topic_cat, topic_by)
						VALUES('" . $mysqli->real_escape_string($topic) ."', NOW(),
							'" . $mysqli->real_escape_string($category) ."',
							'" . $_SESSION['user_id'] . "')";
							
				// create the topic			
				if (!($result = $mysqli->query($sql))) {
					// something wrong with creating the topic
					echo "Something went wrong while creating your topic. Please try again later...";
					echo $mysqli->error;
					$mysqli->query("ROLLBACK;");
				} else {
					// topic created. add a post.
					$topic_id = $mysqli->insert_id;
					
					$sql = "INSERT INTO posts(post_content, post_date, post_topic, post_by)
						VALUES('" . $mysqli->real_escape_string($_POST['post_content']) ."', NOW(),
							'" . $topic_id ."', '" . $_SESSION['user_id'] . "')";
							
					// create the topic post
					if (!($result = $mysqli->query($sql))) {
						// something wrong with creating the post
						echo "Something went wrong while creating a post for your topic. Please try again later...";
						echo $mysqli->error;
						$mysqli->query("ROLLBACK;");
					} else {
						// post was created
						$mysqli->query("COMMIT;");
						$topic_url = "topic.php?id=" . $topic_id;
						echo "Sucessfully created <a href=$topic_url>your new topic.</a><br> Return to the <a href=index.php>forum.</a>";
						echo "<script>setTimeout(\"location.href = '$topic_url';\", 0 * 1000);</script>"; // javascript redirect 0s
					}
				}
			}
		}
	}
	
	// page footer
	include "footer.php";
?>

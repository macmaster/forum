<?php
	include "connect.php";
	include "header.php";

	// topic form
	function print_form($topic="",  $message=""){
		echo '<form method="post" action="">';
		echo "<h3>Create a Post</h3>";
		echo '<input type="hidden" name="topic_cat" value="1"><br>';
		echo 'Post Title: <input type="text" name="topic_subject" value="'.$topic.'"/>';
		echo 'Message: <br><textarea name="post_content">'.$message.'</textarea>';
		echo '<input type="submit" value="Create Post"/></form>';
	}
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
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
				// Check if user is signed in
				$user_id = $_SESSION['user_id'];
				if (!isset($_SESSION['signed_in']) || $_SESSION['signed_in'] == false) {
				 	// echo "<script>setTimeout(\"location.href = 'login.php';\", 0 * 1000);</script>"; 
					$user_id = 1;
				}

				// Save topic subject and message
				$sql = "INSERT INTO topics(topic_subject, topic_date, topic_cat, topic_by)
						VALUES('" . $mysqli->real_escape_string($topic) ."', NOW(),
							'" . $mysqli->real_escape_string($category) ."',
							'" . $user_id . "')";
							
				// create the topic			
				if (!($result = $mysqli->query($sql))) {
					// something wrong with creating the topic
					echo "Something went wrong while creating your topic. Please try again later...";
					echo $mysqli->error;
					echo $sql;
					$mysqli->query("ROLLBACK;");
				} else {
					// topic created. add a post.
					$topic_id = $mysqli->insert_id;
					
					$sql = "INSERT INTO posts(post_content, post_date, post_topic, post_by)
						VALUES('" . $mysqli->real_escape_string($_POST['post_content']) ."', NOW(),
							'" . $topic_id ."', '" . $user_id . "')";
							
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
						echo "Sucessfully created <a href=$topic_url>your new topic.</a><br> Return to the <a href=/>forum.</a>";
						echo "<script>setTimeout(\"location.href = '$topic_url';\", 0 * 1000);</script>"; // javascript redirect 0s
					}
				}
			}
		}
	}


	
	// page footer
	include "footer.php";
?>

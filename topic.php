<?php 
	include "connect.php";
	include "header.php";
	
	// fetch the topic information
	$sql = "SELECT topic_id, topic_subject, topic_date, topic_by FROM topics
		WHERE topic_id = " . $mysqli->escape_string($_GET["id"]) . ";";
	
	if (!($result = $mysqli->query($sql))) {
		// topic fetch error
		echo "The topic information could not be retrieved. Please try again later.";
		echo $mysqli->error();
	} else if ($result->num_rows == 0) {
			echo "The topic does not exist!";
	} else {
		$row = $result->fetch_assoc();
		echo "<h2><p>Comments for: " . $row['topic_subject'] . "</p></h2>";
		
		// fetch the topic posts and poster names
		$sql = "SELECT 
			users.user_id, users.user_name, 
			posts.post_id, posts.post_content, posts.post_date, posts.post_by
			FROM users LEFT JOIN posts ON posts.post_by = users.user_id
			WHERE posts.post_topic = " . $mysqli->escape_string($_GET["id"]) . ";";
		
		if (!($result = $mysqli->query($sql))) {
			// posts fetch error
			echo "The topic posts could not be displayed. Please try again later.";
			echo $mysqli->error;	
		} else if($result->num_rows == 0) {
			echo "There are no posts under this topic yet.";
		} else {
			// prepare the table
			echo "<table class='post_table' border=1>
				<tr><th>By:</th><th>Content</th></tr>";
			
			while ($row = $result->fetch_assoc()) {
				echo '<tr><td>';
				echo '<h3>' . $row['user_name'] . '</h3>';
				echo date("m/d/Y", strtotime($row['post_date']));
				echo "<br>" . date("H:i:s", strtotime($row['post_date']));
				echo '</td><td>';
				echo '<p>' . $row['post_content'] . '</p>';
				echo "</td></tr>";
			}
			echo "</table>"; // close the table
			
			if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
				// reply form
				echo '<div class="center-frame"><form method="post" action="reply.php?id=' . $mysqli->escape_string($_GET["id"]) . '">';
				echo '<h3>Reply:</h3>';
				echo '<textarea name="reply-content"></textarea>';
				echo '<input type="submit" value="Submit reply" />';
				echo '</form></div>';
			} else {
				echo "<h2>You are not <a href='login.php'>signed in!</a></h2>";
			}				
		}
	}
	
	// page footer
	include "footer.php";
?>

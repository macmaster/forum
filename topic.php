<?php 
	include "connect.php";
	include "header.php";
	
	// fetch the topic information
	$sql = "SELECT topic_id, topic_subject, topic_date, topic_by FROM topics
		WHERE topic_id = " . mysql_escape_string($_GET["id"]) . ";";
	$result = mysql_query($sql);
	
	if(!$result){
		// topic fetch error
		echo "The topic information could not be retrieved. Please try again later.";
		echo mysql_error();
	} else{
		// draw topic view.
		if(mysql_num_rows($result) == 0){
			echo "The topic does not exist!";
		} else{
			$row = mysql_fetch_assoc($result);
			echo "<h2><p>Posts for: " . $row['topic_subject'] . "</p></h2>";
			
			// fetch the topic posts and poster names
			$sql = "SELECT 
				users.user_id, users.user_name, 
				posts.post_id, posts.post_content, posts.post_date, posts.post_by
				FROM users LEFT JOIN posts ON posts.post_by = users.user_id
				WHERE posts.post_topic = " . mysql_escape_string($_GET["id"]) . ";";
			$result = mysql_query($sql);
			
			if(!$result){
				// posts fetch error
				echo "The topic posts could not be displayed. Please try again later.";
				echo mysql_error();	
			} else{
				if(mysql_num_rows($result) == 0){
					echo "There are no posts under this topic yet.";
				} else{
					// prepare the table
					echo "<table border=1>
						<tr><th>By:</th><th>Content</th></tr>";
					
					while($row = mysql_fetch_assoc($result)){
						echo '<tr><td>';
						echo '<h3>' . $row['user_name'] . '</h3>';
						echo date("m/d/Y", strtotime($row['post_date']));
						echo "<br>" . date("H:i:s", strtotime($row['post_date']));
						echo '</td><td>';
						echo '<p>' . $row['post_content'] . '</p>';
						echo "</td></tr>";
					}
					echo "</table>"; // close the table
					
					// reply form
					echo '<form method="post" action="reply.php?id=' . mysql_escape_string($_GET["id"]) . '">';
					echo '<h3>Reply:</h3>';
					echo '<textarea name="reply-content"></textarea>';
					echo '<input type="submit" value="Submit reply" />';
					echo '</form>';
				}
			}
		}
	}
	
	// page footer
	include "footer.php";
	?>
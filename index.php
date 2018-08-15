<?php
	include "connect.php";
	include "header.php";

	if (isset($_SESSION['user_name']))
		echo '<h2 style="color:green;">Welcome back '.$_SESSION['user_name'].'!</h2>';
	echo '<img id="bobba" src="img/bobba.png"/><br>';

	$sql = "SELECT users.user_name, topics.topic_id, topics.topic_subject FROM topics
			INNER JOIN users ON users.user_id = topics.topic_by
			ORDER BY topics.topic_id DESC;";

	// fetch topics from db
	if (!($result = $mysqli->query($sql))) {
		// something went wrong with the database
		echo "Something went wrong while retrieving the topics. Please try again later...";
		echo $mysqli->error;
	} else if ($result->num_rows == 0) {
		echo "No topics created yet...";
	} else {
		// prepare the table
		echo '<table class="post_table">';
		echo '<tr><th>Posts</th><th>Last Reply</th></tr>';
		
		// read the topics
		while($topic = $result->fetch_assoc()) {	
			echo '<tr>';
			echo '<td class="leftpart">';
			echo '<h3><a href="topic.php?id=' . $topic['topic_id'] . '">';
			echo $topic['topic_subject'] . '</a></h3>posted by [anonymous]</td>';
			
			// last topic
			$sql = "SELECT topics.topic_id, topics.topic_subject, posts.post_user, posts.post_date, posts.post_content FROM topics 
				INNER JOIN posts ON topics.topic_id = posts.post_topic
				WHERE topics.topic_id = ". $topic['topic_id'] . "
				ORDER BY posts.post_date DESC;";

			if (!($posts = $mysqli->query($sql))) {
				echo "Something went wrong while retrieving the latest replies. Please try again later...";
				echo $mysqli->error;
			} else if ($posts->num_rows == 0) {
				echo '<td class="rightpart">no topics yet..</td>';
				echo '</tr>';
			} else {
				$post = $posts->fetch_assoc();
				echo '<td class="rightpart">';
				echo '<p>'.$post['post_content'].'</p>'; 
				echo 'posted by '.$post['post_user'].'  at '.$post['post_date'].'</td>';
				echo '</tr>';
			}
		}
		
		// close table
		echo '</table>';
	}
	
	// page footer
	include "footer.php";
?>

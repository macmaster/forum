<?php
	include "connect.php";
	include "header.php";

	if (isset($_SESSION['user_name']))
		echo '<h2 style="color:green;">Welcome back '.$_SESSION['user_name'].'!</h2>';
	echo '<img id="bobba" src="img/bobba.png"/><br>';

	$sql = "SELECT cat_id, cat_name, cat_description FROM categories;";

	// fetch categories from db
	if (!($result = $mysqli->query($sql))) {
		// something went wrong with the database
		echo "Something went wrong while retrieving the categories. Please try again later...";
		echo $mysqli->error;
	} else if ($result->num_rows == 0) {
		echo "No categories created yet...";
	} else {
		// prepare the table
		echo '<table>';
		echo '<tr><th>Category</th><th>Last Topic</th></tr>';
		
		// read the categories
		while($category = $result->fetch_assoc()) {	
			// category
			echo '<tr>';
			echo '<td class="leftpart">';
			echo '<h3><a href="category.php?id=' . $category['cat_id'] . '">';
			echo $category['cat_name'] . '</a></h3>' . $category['cat_description'] . '</td>';
			
			// last topic
			$sql = "SELECT topics.topic_id, topics.topic_subject, posts.post_date FROM categories 
				INNER JOIN topics ON topics.topic_cat = categories.cat_id
				INNER JOIN posts ON topics.topic_id = posts.post_topic
				WHERE categories.cat_id = ". $category['cat_id'] . "
				ORDER BY posts.post_date DESC;";

			if (!($posts = $mysqli->query($sql))) {
				echo "Something went wrong while retrieving topic subjects. Please try again later...";
				echo $mysqli->error;
			} else if ($posts->num_rows == 0) {
				echo '<td class="rightpart">no topics yet..</td>';
				echo '</tr>';
			} else {
				$topic = $posts->fetch_assoc();
				echo '<td class="rightpart">';
				echo '<a href="topic.php?id='.$topic['topic_id'].'">'.$topic['topic_subject'].'</a>'; 
				echo '<br> at '.$topic['post_date'].'</td>';
				echo '</tr>';
			}
		}
		
		// close table
		echo '</table>';
	}
	
	// page footer
	include "footer.php";
?>

<?php 
	include "connect.php";
	include "header.php";
	
	// fetch the category information
	$sql = "SELECT cat_id, cat_name, cat_description FROM categories
		WHERE cat_id = " . $mysqli->escape_string($_GET["id"]) . ";";
	
	if (!($result = $mysqli->query($sql))) {
		// category fetch error
		echo "The category could not be displayed. Please try again later.";
		echo $mysqli->error;
	} else if ($result->num_rows == 0) {
			echo "The category does not exist!";
	} else {
		$row = $result->fetch_assoc();
		echo "<h2>Topics in " . $row['cat_name'] . " category</h2>";
		
		// query the topics
		$sql = "SELECT topic_id, topic_subject, topic_date, topic_cat FROM topics
			WHERE topic_cat = " . $mysqli->escape_string($_GET['id']) . ";";
		
		if (!($result = $mysqli->query($sql))) {
			echo "The topics could not be displayed. Please try again later.";
		} else if ($result->num_rows == 0) {
			echo "There are no topics in this category yet.";
		} else {
			// prepare the table
			echo "<table border=1>
				<tr><th>Topic</th>
				<th>Created on</th></tr>";
			
			while ($row = $result->fetch_assoc()) {
				echo '<tr><td class="leftpart">';
				echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a></h3>';
				echo '</td><td class="rightpart">';
				echo date("d-m-Y", strtotime($row['topic_date']));
				echo "</td></tr>";
			}
			echo "</table>"; // close the table
		}
	}
	
	// page footer
	include "footer.php";
?>

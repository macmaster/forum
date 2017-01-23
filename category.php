<?php 
	include "connect.php";
	include "header.php";
	
	// fetch the category information
	$sql = "SELECT cat_id, cat_name, cat_description FROM categories
		WHERE cat_id = " . mysql_escape_string($_GET["id"]) . ";";
	$result = mysql_query($sql);
	
	if(!result){
		// category fetch error
		echo "The category could not be displayed. Please try again later.";
		echo mysql_error();
	} else{
		// draw category view.
		if(mysql_num_rows($result) == 0){
			echo "The categorydoes not exist!";
		} else{
			$row = mysql_fetch_assoc($result);
			echo "<h2>Topics in " . $row['cat_name'] . " category</h2>";
			
			// query the topics
			$sql = "SELECT topic_id, topic_subject, topic_date, topic_cat FROM topics
				WHERE topic_cat = " . mysql_escape_string($_GET['id']) . ";";
			$result = mysql_query($sql);
			
			if(!$result){
				echo "The topics could not be displayed. Please try again later.";
			} else{
				if(mysql_num_rows($result) == 0){
					echo "There are no topics in this category yet.";
				} else{
					// prepare the table
					echo "<table border=1>
						<tr><th>Topic</th>
						<th>Created on</th></tr>";
					
					while($row = mysql_fetch_assoc($result)){
						echo '<tr><td class="leftpart">';
						echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a></h3>';
						echo '</td><td class="rightpart">';
						echo date("d-m-Y", strtotime($row['topic_date']));
						echo "</td></tr>";
					}
					echo "</table>"; // close the table
				}
			}
		}
	}
	
	// page footer
	include "footer.php";
	?>
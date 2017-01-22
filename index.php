<?php
	include "connect.php";
	include "header.php";
	echo '<img id="bobba" src="img/bobba.png"/><br>';
	
	$sql = "SELECT
				cat_id, cat_name, cat_description
			FROM
				categories";
				
	// execute query			
	$result = mysql_query($sql);
	if(!$result){
		// something went wrong with the database
		echo "Something went wrong while retrieving the categories. Please try again later...";
		echo mysql_error();
	} else{
		if(mysql_num_rows($result) == 0){
			echo "No categories created yet...";
		} else{
			// prepare the table
			echo '<table><tr>';
			echo '<th>Category</th><th>Last Topic</th>';
			echo '</tr>';
			
			// fetch the categories
			while($category = mysql_fetch_assoc($result)){	
				// category
				echo '<tr>';
				echo '<td class="leftpart">';
				echo '<h3><a href="category.php?id=' . $category['cat_id'] . '">';
				echo $category['cat_name'] . '</a></h3>' . $category['cat_description'] . '</td>';
				
				// last topic
				echo '<td class="rightpart">';
				echo '<a href="topic.php?id=">Topic Subject</a>'; 
				echo ' at 12-31-2017</td>';
				echo '</tr>';
			}
			
			// close table
			echo '</table>';
		}
	}
	
	// page footer
	include "footer.php";
?>

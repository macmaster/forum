<?php
	include "connect.php";
	include "header.php";

	// category form
	function print_form($category="", $description=""){
		echo '
			<form method="post" action="">
			<h3>Create a Category</h3>
				Category name: <input type="text" name="cat_name" value="'.$category.'"/><br>
				Category description: <br><textarea name="cat_description">'.$description.'</textarea>
				<input type="submit" value="Add Category">
			</form>';
	}
	
	// returns true if category already exists
	function category_exists($category){
		global $mysqli;
		$result = $mysqli->query("SELECT 1 FROM categories WHERE cat_name = '$category'");
		if($result && $result->num_rows > 0){
			return true; // category already exists
		} else{
			return false;
		}
	}
	
	// Check if user is an administrator
	if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] != 1) {
		echo 'You must be an administrator to create a category. <br>';
		echo 'Return to the <a href=index.php>homepage.</a>';
		echo "<script>setTimeout(\"location.href = 'index.php';\", 3 * 1000);</script>"; // javascript redirect 3s
	} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		print_form(); // Form hasn't been posted yet. display it.
	} else { 
		$errors = array();
		unset($name); unset($desc);
		
		// check category name
		if (!isset($_POST['cat_name']) || !($category = $_POST['cat_name'])) {
			$errors[] = "Category name must not be empty!";
		} else if (category_exists($category)) {
			$errors[] = "Category already exists!";
		}
		
		// check category description
		if(!isset($_POST['cat_description']) || !($description = $_POST['cat_description'])) {
			$errors[] = "Category description must not be empty!";
		}
		
		// check for errors
		if (!empty($errors)) {
			echo "Uh-oh.. a couple of the fields are not filled in correctly..";
			echo "<ul>";
			foreach($errors as $key => $value){
				echo "<li>$value</li>";
			}
			echo "</ul>";
			print_form($category, $description);
		} else { 
			// Save category data
			$sql = "INSERT INTO	categories(cat_name, cat_description)
					VALUES('" . $mysqli->real_escape_string($category) ."',
						'" . $mysqli->real_escape_string($description) . "')";

			if(!($result = $mysqli->query($sql))) {
				// something went wrong with the database
				echo "Something went wrong while creating the category. Please try again later...";
				echo $mysqli->error;
			} else {
				echo "Sucessfully created the category.<br> Return to the <a href=index.php>forum.</a>";
				echo "<script>setTimeout(\"location.href = 'index.php';\", 3 * 1000);</script>"; // javascript redirect 3s
			}
		}
	}
	
	// page footer
	include "footer.php";
?>

<?php
$server =  "localhost";
$username = "ronny";
$password = "Macmaster17";
$database = "forum";

// connect to the database
if(!mysql_connect($server, $username, $password)){
	exit("Error: could not establish database connection.");
} else if(!mysql_select_db($database)){
	exit("Error: could not select the database.");
}
?>
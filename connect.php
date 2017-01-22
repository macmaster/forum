<?php
$server =  "localhost";
$username = "ronny";
$password = "3mjnNs79GGSf9w7s";
$database = "forum";

// connect to the database
if(!mysql_connect($server, $username, $password)){
	exit("Error: could not establish database connection.");
} else if(!mysql_select_db($database)){
	exit("Error: could not select the database.");
}
?>
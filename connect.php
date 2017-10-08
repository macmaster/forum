<?php
$server =  "localhost";
$username = "ronny";
$password = "Macmaster17";
$database = "forum";

// connect to the database
$mysqli = new mysqli($server, $username, $password, $database);
if ($mysqli->connect_error) {
	exit("MYSQL Error ($mysqli->connect_errno): $mysqli->connect_error");
} 
?>

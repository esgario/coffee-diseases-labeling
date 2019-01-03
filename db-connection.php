<?php

$hostname_conn = "localhost";
$username_conn = "root";
$password_conn = "mydb1234";
$database_conn = "coffee_leaf";

$mysqli = new mysqli($hostname_conn, $username_conn, $password_conn, $database_conn);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MYSQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$mysqli->set_charset("utf8")

?>
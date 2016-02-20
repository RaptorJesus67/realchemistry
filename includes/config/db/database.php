<?php
	
	$host = "localhost";
	$user = "root";
	$pass = "xb123MA1CD#47b";
	$data = "offstreams";
	
	$conn = new mysqli($host, $user, $pass, $data);

	/*
	 * This is the "official" OO way to do it,
	 * BUT $connect_error was broken until PHP 5.2.9 and 5.3.0.
	 */
	if ($conn->connect_error) {
		die('Connect Error (' . $conn->connect_errno . ') '
				. $conn->connect_error);
	}
	
?>

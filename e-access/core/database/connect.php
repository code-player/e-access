<?php
	$servername = "localhost";
	$username = "";
	$password = "";

	function connect_error_page()
	{	header('Location:../pages/connect_error.php');
		exit();
		}

	$conn = mysqli_connect($servername, $username, $password,'e_access');
	$conn1 = mysqli_connect($servername, $username, $password,'result');

	if (!$conn)
	   die("Connection failed: ".connect_error_page());
	if (!$conn1)
	   die("Connection failed: ".connect_error_page());
?>

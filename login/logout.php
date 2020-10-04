<?php
	session_start();
	if(!isset($_SESSION['userid']))
	{
		echo '<script>alert("You are not logged in.");
			window.location.href = "/index.php";</script>';
		exit();
	}

	session_destroy();
	echo '<script>alert("You were logged out.");
			window.location.href = "/index.php";</script>';
	exit();
?>

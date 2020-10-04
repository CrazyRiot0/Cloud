<?php
	session_start();

	$id = $_POST['id'];
	$pw = $_POST['pw'];

	include("mysqli.php");

	$query = "SELECT * FROM acc WHERE id='$id'";
	$result = $mysqli -> query($query);
	if($result -> num_rows==1)
	{
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		if($row['pw'] == $pw)
		{
			$_SESSION['userid'] = $id;
			if(isset($_SESSION['userid']))
			{
				header("Location: /index.php");
				/*echo '<script>alert("Login Success.");
					window.location.href = "/index.php";</script>';*/
				exit();
			}
			else
			{
				echo '<script> alert("Session Save Error. Back to Login page.");
					window.location.href = "/login/index.php"; </script>';
				exit();
			}
		}
		else
		{
			echo '<script> alert("The ID or Password is wrong.");
					window.location.href = "/login/index.php"; </script>';
			exit();
		}
	}
	else
	{
		echo '<script> alert("The ID or Password is wrong.");
				window.location.href = "/login/index.php"; </script>';
		exit();
	}
?>

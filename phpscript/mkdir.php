<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	$dirName = $_POST['path'];
	$dirName = str_replace(" ", "_", $dirName);
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$dirName;
	if(!file_exists($path))
	{
		if(mkdir($path, 0777, true))
		{
			echo "Success";
		}
		else
		{
			echo "Error";
		}
	}
?>

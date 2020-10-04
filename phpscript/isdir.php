<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$_GET['path'];
	if(is_dir($path))
		echo "True";
	else
		echo "False";
	exit();
?>

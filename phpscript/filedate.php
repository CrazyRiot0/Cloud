<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$_GET['path'];
	$filedate = date("F d Y H:i:s", filectime($path));
	echo $filedate;
?>

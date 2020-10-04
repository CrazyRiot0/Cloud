<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$_GET['path'];
	$files = array_diff(scandir($path), array('.', '..'));
	foreach($files as $file)
	{
		echo $file;
		echo "\n";
	}
?>

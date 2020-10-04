<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$_GET['path'];
	if(is_dir($path))
		$isdir = "True";
	else
		$isdir = "False";
	$filesize = filesize($path);
	$filedate = date("F d Y H:i:s", filectime($path));
	echo "<div id='isdir'>".$isdir."</div>";
	echo "<div id='filesize'>".$filesize."</div>";
	echo "<div id='filedate'>".$filedate."</div>";
?>

<?php
	$path = $_GET['p'];
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$path;
	include("phpscript/downloadToClient.php");
	downloadToClient($path);
	exit();
?>

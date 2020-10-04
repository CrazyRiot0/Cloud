<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	include("downloadToClient.php");
	include("Zip.php");
	$cloudPath = $_SERVER['DOCUMENT_ROOT']."/cloud/data/";
	$serverRoot = $_SERVER['DOCUMENT_ROOT'];
	$files = json_decode($_POST['files']);
	if(!is_array($files))
	{
		$files = array($files);
	}
	if(count($files) == 1)
	{
		if($files[0]->isDir) //Folder
		{
			$date = date("m-d-Y");
			$zipname = "Files-".$date.".zip";
			$zipname = $serverRoot."/tmp/".$zipname;
			$path = $cloudPath.$files[0]->path;
			Zip($path, $zipname);
			downloadToClient($zipname);
			unlink($zipname);
			exit();
		}
		else
		{
			$path = $cloudPath.$files[0]->path;
			downloadToClient($path);
			exit();
		}
	}

	$paths = [];
	foreach($files as $f)
	{
		array_push($paths, $cloudPath.$f->path);
	}

	$date = date("m-d-Y");
	$zipname = "Files-".$date.".zip";
	$zipname = $serverRoot."/tmp/".$zipname;
	Zip($paths, $zipname);

    downloadToClient($zipname);
	unlink($zipname);
?>

<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	$path = $_SERVER['DOCUMENT_ROOT']."/cloud/data/".$_GET['path'];
	foreach($_FILES['upload']['name'] as $f => $name)
	{
		$name = $_FILES['upload']['name'][$f];
		$name = str_replace(" ", "_", $name);
		$uploadPath = $path.$name;
		if(move_uploaded_file($_FILES['upload']['tmp_name'][$f], $uploadPath))
		{
			echo "Success\n";
		}
		else
		{
			echo "Error\n";
		}
	}
?>

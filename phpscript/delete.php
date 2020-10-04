<?php
    session_start();
    if(!isset($_SESSION['userid']))
        exit();
    include("deleteDir.php");
    $serverPath = $_SERVER['DOCUMENT_ROOT']."/cloud/data/";
    $files = json_decode(file_get_contents("php://input"));
    foreach($files as $f)
    {
        $path = $serverPath.$f->path;
        if($f->isDir)
        {
            deleteDir($path);
        }
        else
        {
            unlink($path);
        }
    }
?>

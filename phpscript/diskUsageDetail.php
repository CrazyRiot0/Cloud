<?php
	session_start();
	if(!isset($_SESSION['userid']))
		exit();
	include('formatBytes.php');
	$df = disk_free_space("/");
	$ds = disk_total_space("/");
	$du = $ds - $df;

	echo formatBytes($df, 1)." free of ".formatBytes($ds, 1)."<br>(".formatBytes($du)." used) (".bcdiv($df*100, $ds, 1)."% free)";
?>

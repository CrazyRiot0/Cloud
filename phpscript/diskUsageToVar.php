<?php
	$df = disk_free_space("/");
	$ds = disk_total_space("/");
	$du = $ds - $df;
?>

<div id="ds"><?php echo $ds; ?></div>
<div id="du"><?php echo $du; ?></div>
<div id="df"><?php echo $df; ?></div>

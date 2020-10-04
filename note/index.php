<?php
	session_start();
	if(!isset($_SESSION['userid']))
	{
		header("Location: /login/index.php");
		exit();
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Test</title>
		<link rel="icon" type="image/x-icon" href="/cloud/res/icon_cloud.ico">
		<link rel="stylesheet" type="text/css" href="/css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="tabStyle.css">
	</head>
	<body>
		<h1>Ubuntu Server Cloud <span style="font-size:15px;">Account: <?php echo $_SESSION['userid']; ?></span>
		<button onclick="location.href='/login/logout.php'">Logout</button>
		<button onclick="location.href='/login/withdraw.php'">Remove Account</button></h1>
		<br>
		<div class="tab_wrap">
			<div class="tab_menu_container">
				<button class="tab_menu" id="btn_1">테스트1</button>
				<button class="tab_menu" id="btn_2">테스트2</button>
			</div>
			<div class="tab_box_container">
				<div class="tab_box" id="box_1">1번결과</div>
				<div class="tab_box" id="box_2">2번결과다</div>
			</div>
	</body>
</html>

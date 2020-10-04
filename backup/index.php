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
		<title>Ubuntu Server Cloud</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="/script/formatBytes.js"></script>
		<script>
			var currentPath = "";
			var files = [];

			function filesToObject(path) {
				$.get("/phpscript/ls.php?path="+path, function(data) {
					files = [];
					var array = data.split(" ");
					array.forEach(function(element) {
						if(element=="") {
							return false;
						}
						$.get("/phpscript/isdir.php?path="+path+element, function(data_isdir) {
							$.get("/phpscript/filesize.php?path="+path+element, function(data_filesize) {
								var isDir;
								if(data_isdir == "True") {
									isDir = true;
								}
								else if(data_isdir == "False") {
									isDir = false;
								}
								else {
									isDir = -1;
								}

								var object = {
									fileName: element,
									path: path+element,
									isDir: isDir,
									size: data_filesize,
									date: 0
								};
								files.push(object);
							});
						});
					});
				});
				function compare(a, b) {
					if(a.isDir > b.isDir) {
						return 0;
					}
					if(a.fileName < b.fileName) {
						return -1;
					} else if(a.fileName > b.fileName) {
						return 1;
					} else {
						return 0;
					}
				}
				files.sort(compare);

				return files;
			}
		</script>

		<script>
			$(document).ready(function() {
				$("#dusage_refresh").click(function() {
					$.get("/phpscript/diskUsageToVar.php", function(data) {
						var array = $(data).text().split("\n");
						var ds = array[0];
						var du = array[1];
						var df = array[2];
						$("#dusage_bar").attr("value", du);
						$("#dusage_bar").attr("max", ds);
						// Now for #dusage_detail
						$("#dusage_free").html(formatBytes(df, 1));
						$("#dusage_size").html(formatBytes(ds, 1));
						$("#dusage_usage").html(formatBytes(du, 1));
						$("#dusage_free_percent").html(parseFloat(df/ds*100).toFixed(1));
					});
				}).trigger("click");
			});
		</script>
		<script>
			$(document).ready(function() {
				$("#list_refresh").click(function() {
					$("#list").empty();
					filesToObject(currentPath).forEach(function(element) {
						var S = "<a href='www.google.com'>" + element.fileName + "</a><br>";
						$("#list").append(S);
					});
				}).trigger("click");

				$("#exp_refresh").click(function() {
					var tbody = document.getElementById("exp_tbody");
					filesToObject(currentPath).forEach(function(element) {
						var row = tbody.insertRow(tbody.rows.length);
						var name = row.insertCell(0);
						var size = row.insertCell(1);
						var date = row.insertCell(2);
						name.innerHTML = element.fileName;
						size.innerHTML = element.size;
						date.innerHTML = element.date;
					});
				}).trigger("click");
			});
		</script>
	</head>
	<body>
		<h1>Ubuntu Server Cloud <span style="font-size:15px;">Account: <?php echo $_SESSION['userid']; ?></span>
		<button onclick="location.href='/login/logout.php'">Logout</button>
		<button onclick="location.href='/login/withdraw.php'">Remove Account</button></h1>

		<fieldset id="info" style="float:left; width:230px; height:600px; position: relative;">
			<legend><b>Info</b> <button id="list_refresh">Refresh</button></legend>
			Path : <span id="path"></span><br>
			<div id="list"></div>
			<fieldset style="width:200px; position: absolute; bottom: 0;">
				<legend>Disk Usage <button id="dusage_refresh">Refresh</button></legend>
				<progress id="dusage_bar" value="<?php echo $du; ?>" max="<?php echo $ds; ?>" style="width:170px; height:20px;"></progress><br>
				<span id="dusage_detail"><span id="dusage_free"></span> free of <span id="dusage_size"></span><br>
					(<span id="dusage_usage"></span> used) (<span id="dusage_free_percent"></span>% free)</span>
			</fieldset>
		</fieldset>
		<fieldset id="explorer" style="float:left; width:1100px; height:600px;">
			<legend><b>File Explorer</b> <button id="exp_refresh">Refresh</button></legend>
			<table style="width:1000px;">
				<thead>
					<th style="width:500px; height:50px;">Name</th>
					<th style="width:200px; height:50px;">Size</th>
					<th style="width:250px; height:50px;">Date</th>
				</thead>
				<tbody id="exp_tbody" style="text-align: center; font-size:15px;"></tbody>
			</table>
		</fieldset>
	</body>
</html>

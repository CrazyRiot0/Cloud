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
		<link rel="icon" type="image/x-icon" href="/cloud/res/icon_cloud.ico">
		<link rel="stylesheet" type="text/css" href="/css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="/script/formatBytes.js"></script>
		<script>
			var currentPath = "";
			function dirDepth(path) {
				var list = path.split("/");
				list.pop();
				return list.length;
			}
		</script>
		<script>
			var files = filesToObject(currentPath);

			function sortFiles(files) {
				var files_file = files.filter(function(x) {
					return !x.isDir;
				});
				files_file.sort(function(a, b) {
					return a.fileName > b.fileName;
				});
				var files_folder = files.filter(function(x) {
					return x.isDir;
				});
				files_folder.sort(function(a, b) {
					return a.fileName > b.fileName;
				});
				files = files_folder.concat(files_file);

				var i = 1;
				files.forEach(function(element) {
					element.index = i++;
				});

				return files;
			}

			function filesToObject(path) {
				$.ajax({
					url: "/phpscript/ls.php?path="+path,
					type: "GET",
					async: false,
					success: function(data) {
						files = [];
						var array = data.split("\n");
						var i = 1;
						array.forEach(function(element) {
							if(element=="") {
								return false;
							}
							$.ajax({
								url: "/phpscript/fileinfo.php?path="+path+element,
								type: "GET",
								async: false,
								success: function(info) {
									var isdir = $(info).filter("#isdir").text();
									if(isdir=="True") {
										isdir = true;
									} else {
										isdir = false;
									}
									var filesize = $(info).filter("#filesize").text();
									var filedate = $(info).filter("#filedate").text();
									var object = {
										index: null,
										fileName: element,
										path: path+element,
										isDir: isdir,
										size: filesize,
										date: filedate
									};
									files.push(object);
								}
							})
						})
					}
				})

				return files;
			}

			function refresh() {
				files = filesToObject(currentPath);
				files = sortFiles(files);
				$("#exp_path").html(currentPath);
				$("#exp_tbody").empty();
				var tbody = document.getElementById("exp_tbody");
				if(dirDepth(currentPath) > 0) {
					var row = tbody.insertRow(tbody.rows.length);
					row.insertCell(0).innerHTML = "";
					var type = row.insertCell(1);
					var name = row.insertCell(2);

					var img_folder = document.createElement("img");
					img_folder.src = "/cloud/res/icon_folder.png";
					img_folder.width = "20";
					img_folder.height = "20";
					type.appendChild(img_folder);
					
					var link = document.createElement("a");
					link.innerHTML = "Previous Folder";
					link.href = "javascript:void(0);";
					link.onclick = function() {
						fileHandler("..");
					}
					name.appendChild(link);
				}
				var i = 1;
				files.forEach(function(element) {
					var row = tbody.insertRow(tbody.rows.length);
					var select = row.insertCell(0);
					var type = row.insertCell(1);
					var name = row.insertCell(2);
					var size = row.insertCell(3);
					var date = row.insertCell(4);

					var checkBox = document.createElement("input");
					checkBox.type = "checkbox";
					checkBox.id = "checkbox_"+element.index;
					select.appendChild(checkBox);

					if(element.isDir) {
						var img_folder = document.createElement("img");
						img_folder.src = "/cloud/res/icon_folder.png";
						img_folder.width = "20";
						img_folder.height = "20";
						type.appendChild(img_folder);
					} else {
						var img_file = document.createElement("img");
						img_file.src = "/cloud/res/icon_file.png";
						img_file.width = "20";
						img_file.height = "20";
						type.appendChild(img_file);
					}
					var link = document.createElement("a");
					link.innerHTML = element.fileName;
					link.href = "javascript:void(0);";
					link.onclick = function() {
						fileHandler(element.index);
					};
					name.appendChild(link);
					if(!element.isDir) {
						size.innerHTML = formatBytes(element.size, 1);
					}
					date.innerHTML = element.date;
				});
			}

			function fileHandler(fileIdx) {
				if(fileIdx == "..") {
					var list = currentPath.split("/");
					list.pop();
					list.pop();
					if(list.length == 0) {
						currentPath = "";
						refresh();
						return;
					}
					var S = "";
					list.forEach(function(element) {
						S += element + "/";
					})
					currentPath = S;
					refresh();
					return;
				}
				var object = files.find(obj => obj.index === fileIdx);
				if(object.isDir) {
					currentPath += object.fileName + "/";
					refresh();
				} else if(!object.isDir) {
					post("/phpscript/downloadMultiple.php", {files: JSON.stringify(object)});
				}
			}

			function setProgress(id, value, max) {
				$("#"+id).attr("value", value);
				$("#"+id).attr("max", max);
			}

			function post(path, params, method='post') {
				// The rest of this code assumes you are not using a library.
				// It can be made less wordy if you use one.
				const form = document.createElement('form');
				form.method = method;
				form.action = path;

				for (const key in params) {
					if (params.hasOwnProperty(key)) {
						const hiddenField = document.createElement('input');
						hiddenField.type = 'hidden';
						hiddenField.name = key;
						hiddenField.value = params[key];
						form.appendChild(hiddenField);
					}
				}
				document.body.appendChild(form);
				form.submit();
			}
		</script>

		<script>
			$(document).ready(function() {
				refresh();
				$("#dusage_refresh").click(function() {
					$.get("/phpscript/diskUsageToVar.php", function(data) {
						var array = $(data).text().split("\n");
						var ds = array[0];
						var du = array[1];
						var df = array[2];
						setProgress("dusage_bar", du, ds);
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
				$("#exp_refresh").click(function() {
					refresh();
				});
				$("#exp_upload_input").change(function(e) {
					e.preventDefault();
					var formData = new FormData();
					var uploadInput = document.getElementById("exp_upload_input");
					var len = uploadInput.files.length;
					var fileNames = [];
					for(var x = 0; x < len; x++) {
						formData.append("upload[]", document.getElementById("exp_upload_input").files[x]);
						fileNames.push(uploadInput.files[x].name);
					}

					var progressField, legend, progressBar, progressDetail;
					$.ajax({
						url: "/phpscript/upload.php?path="+currentPath,
						type: "POST",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						xhr: function() {
							//last
							var infoDiv = document.getElementById("info");

							progressField = document.createElement("fieldset");
							progressField.style.width = "200px";
							legend = document.createElement("legend");
							legend.innerHTML = "Uploading ";
							var limit = 2;
							if(fileNames.length <= limit)
								legend.innerHTML += fileNames.toString();
							else
								legend.innerHTML += fileNames.slice(0, limit).toString()+" ... ("+fileNames.length+" files)";
							progressBar = document.createElement("progress");
							progressBar.style.width = "160px";
							progressBar.style.height = "20px";
							progressDetail = document.createElement("label");
							progressField.appendChild(legend);
							progressField.appendChild(progressBar);
							progressField.appendChild(progressDetail);
							infoDiv.appendChild(progressField);

							var xhr = new window.XMLHttpRequest();
							var upSpeed, lastUpTime, endTime, uploaded;
							xhr.upload.addEventListener("progress", function(e) {
								progressBar.value = e.loaded;
								progressBar.max = e.total;
								var percentage = (e.loaded/e.total*100).toFixed(1);
								var detail = "<br>"+percentage+"%";

								var endTime = (new Date()).getTime();
								upSpeed = ((e.loaded - uploaded)) / ((endTime - lastUpTime) / 1000);
								detail += " ("+formatBytes(upSpeed, 1)+"/s)";
								uploaded = e.loaded;
								lastUpTime = endTime;

								detail += "<br>("+formatBytes(e.loaded, 1)+" / "+formatBytes(e.total, 1)+")";

								var eta = ((e.total-e.loaded)/upSpeed).toFixed(0);
								var ETA = new Date(eta * 1000).toISOString().substr(11, 8);
								detail += "<br>(ETA " + ETA + ")";

								progressDetail.innerHTML = detail;
							}, false);
							return xhr;
						},
						success: function() {
							progressDetail.innerHTML = "Complete";
							setTimeout(function() {
								progressField.parentNode.removeChild(progressField);
							}, 3000);
							refresh();
						},
						error: function() {
							alert("AJAX Request Error.");
						}
					});
				});
				$("#exp_delete").click(function() {
					if(files.length == 0) {
						return;
					}
					var checkedList = [];
					for(var i = 1; i <= files.length; i++) {
						var element = document.getElementById("checkbox_"+i);
						if(element.checked) {
							checkedList.push(i);
							element.checked = false;
						}
					}
					if(checkedList.length == 0) {
						alert("No Files are Selected.");
						return;
					}
					var checkedFiles = [];
					var fileNames = [];
					checkedList.forEach(function(element) {
						var result = files.find(obj => {
							return obj.index === element;
						});
						checkedFiles.push(result);
						fileNames.push(result.fileName);
					});

					var fileNamesToStr;
					var limit = 2;
					if(fileNames.length <= limit)
						fileNamesToStr = fileNames.toString();
					else
						fileNamesToStr = fileNames.slice(0, limit).toString()+" ... ("+fileNames.length+" files)";
					if(!confirm("Delete " + fileNamesToStr + "?"))
						return;

					$.ajax({
						url: "/phpscript/delete.php",
						type: "POST",
						data:  JSON.stringify(checkedFiles),
						contentType: "application/json",
						success: function() {
							refresh();
						},
						error: function() {
							alert("AJAX Error.");
						}
					});
				});
				$("#checkbox_toggle").click(function() {
					if(files.length == 0) {
						return;
					}
					var allChecked = true;
					for(var i = 1; i <= files.length; i++) {
						var element = document.getElementById("checkbox_"+i);
						if(!element.checked) {
							allChecked = false;
						}
					}
					for(var i = 1; i <= files.length; i++) {
						var element = document.getElementById("checkbox_"+i);
						if(allChecked) {
							element.checked = false;
						} else {
							element.checked = true;
						}
					}
				});
				$("#exp_mkdir").click(function() {
					var dirName = prompt("New Folder Name", "");
					if(dirName == null)
						return;
					if(dirName == "") {
						alert("Please Insert Folder Name.");
						return;
					}
					var object = files.find(obj => obj.fileName === dirName);
					if(typeof object != "undefined") {
						alert("Folder Exists.");
						return;
					}
					var path = currentPath + dirName;
					$.ajax({
						url: "/phpscript/mkdir.php",
						type: "POST",
						data: {"path": path},
						success: function() {
							refresh();
						},
						error: function() {
							alert("AJAX Error");
						}
					});
				});
				$("#exp_download").click(function() {
					if(files.length == 0) {
						return;
					}
					var object = [];
					for(var i = 1; i <= files.length; i++) {
						var element = document.getElementById("checkbox_"+i);
						if(element.checked) {
							object.push(files[i-1]);
						}
					}
					if(object.length == 0) {
						return;
					}
					post("/phpscript/downloadMultiple.php", {files: JSON.stringify(object)});
				});
			});
		</script>
	</head>
	<body>
		<h1>Ubuntu Server Cloud <span style="font-size:15px;">Account: <?php echo $_SESSION['userid']; ?></span>
		<button onclick="location.href='/login/logout.php'">Logout</button>
		<button onclick="location.href='/login/withdraw.php'">Remove Account</button>
		<div id="test"></div></h1>

		<fieldset id="info_field" style="float:left; width:230px; height:600px; position: relative;">
			<legend><b>Info</b></legend>
			<div id="info">
				<fieldset id="upload_progress" style="width:200px; display: none;">
					<legend id="upload_progress_title" style="width:180px;"></legend>
					<progress id="upload_progressbar" style="width:160px; height:20px;"></progress>
					<label id="upload_progress_detail"></label>
				</fieldset>
			</div>
			<fieldset style="width:200px; position: absolute; bottom: 0;">
				<legend>Disk Usage <button id="dusage_refresh">Refresh</button></legend>
				<progress id="dusage_bar" style="width:170px; height:20px;"></progress><br>
				<span id="dusage_detail"><span id="dusage_free"></span> free of <span id="dusage_size"></span><br>
					(<span id="dusage_usage"></span> used) (<span id="dusage_free_percent"></span>% free)</span>
			</fieldset>
		</fieldset>
		<fieldset id="explorer" style="width:1100px; height:600px;">
			<legend><b>File Explorer</b>
				<form id="exp_upload_form" enctype="multipart/form-data" style="display: none;">
					<input type="file" name="upload[]" id="exp_upload_input" multiple>
				</form>
				<button>
					<label for="exp_upload_input" style="font-size: 16px;">Upload</label>
				</button>
				<button id="exp_mkdir" style="font-size: 16px;">New Folder</button>
				<button id="exp_download" style="font-size: 16px;">Download</button>
				<button id="exp_delete" style="font-size: 16px;">Delete</button>
				<button id="exp_refresh" style="font-size: 16px;">Refresh</button>
			</legend>
			&nbsp;<span id="exp_path" style="font-size: 16px;"></span>
			<table id="explorer" style="text-align: left; width:1000px;">
				<thead>
					<th style="width:20px; height:50px;"><button id="checkbox_toggle">&nbsp;</button></th>
					<th style="width:25px; height:50px;"></th>
					<th style="width:500px; height:50px;">Name</th>
					<th style="width:100px; height:50px;">Size</th>
					<th style="width:250px; height:50px;">Date</th>
				</thead>
				<tbody id="exp_tbody" style="text-align: left; font-size:15px;"></tbody>
			</table>
		</fieldset>
	</body>
</html>

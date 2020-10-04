<html>
	<head>
		<meta charset="utf-8">
		<title>File Uploader</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="/script/randomString.js"></script>
		<script src="/script/formatBytes.js"></script>
	</head>
	<body>
		<h1>File Uploader</h1>
		<form id="upload_form" onsubmit="return false;">
			<input type="file" name="upload[]" id="upload_input" multiple>
			<button style="font-size: 16px;">Upload</button>
		</form>
	</body>
</html>

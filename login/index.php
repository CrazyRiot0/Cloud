<html>
	<head>
		<meta charset="utf-8">
		<link rel="icon" type="image/x-icon" href="/cloud/res/icon_cloud.ico">
		<link rel="stylesheet" type="text/css" href="/css/style.css">
		<title>Ubuntu Server Cloud - Login</title>
		<link rel="stylesheet" href="/css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<body>
		<h1>Ubuntu Server Cloud - Login</h1>

		<fieldset style="width:400px">
			<legend>Login</legend>
			<form action="login_mysql.php" method="post">
				<table>
					<tr>
						<th>ID</th>
						<td><input type="text" name="id"></td>
					</tr>
					<tr>
						<th>Password</th>
						<td><input type="password" name="pw"></td>
					</tr>
				</table>
				<br>
				<button type="submit">Login</button>
				<button onclick="location.href='signup.php'">Signup</button>
			</form>
		</fieldset>
	</body>
</html>

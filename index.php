<?php
session_start();

if (!file_exists('./private')) {
	mkdir('./private');
}
if (!file_exists('./private/passwd')) {
	file_put_contents('./private/passwd', null);
}
?>
<!DOCTYPE html>
<html><head>
		<meta charset="utf-8">
		<title>Testmaggle</title>
</head>
<body>
	Salut tu veux tester quoi ? <br />
	<a href="create_account.php">Create</a><br />
	<a href="login.php">Login</a><br />
	<a href="delete_user.php">Delete</a><br />
</body></html>

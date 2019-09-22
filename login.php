<?php
	require_once('auth_user.php');
	require_once('is_in_csv.php');

	session_start();

	if ($_POST['login'] && $_POST['passwd']) {
		if (auth_user($_POST['login'], $_POST['passwd'])) {
			$_SESSION['logged_on_user'] = $_POST['login'];
			if (is_in_csv('./private/passwd', '1', 'admin')) {
				$_SESSION['admin'] == "1";
			}
			else
				$_SESSION['admin'] == "0";
			header('Location: loginsucc.php');
		}
		else {
			$_SESSION['logged_on_user'] = "";
			echo "Mauvais Id";
		}
	}
?>

<!DOCTYPE html>
<html><head>
	<meta charset="utf-8">
	<title>Login</title>
</head>
<body>
	<form action="login.php" method="POST">
		Identifiant: <input type="text" name="login" value="" />
		<br />
		Mot de passe: <input type="password" name="passwd" value"" />
		<br />
		<input type="submit" name="submit" value="OK" />
	</form>
	<a href="create_account.php">Créer un utilisateur</a><br />
	<a href="delete_user.php">Supprimer un utilisateur</a><br />
</body>
</html>

<?php

	session_start();
	require_once('is_in_csv.php');

	if ($_POST['login'] && $_POST['passwd'] && $_POST['submit'] && $_POST['submit'] === "OK") {
		if ($_POST['login'] == $_SESSION['logged_on_user'] || $_SESSION['admin'] == "1") {
			$i = 0;
			$account = unserialize(file_get_contents('./private/passwd'));
			foreach ($account as $user) {
				if ($user['login'] == $_POST['login']) {
					break;
				}
				$i++;
			}
			array_splice($account, $i, 1);
			file_put_contents('./private/passwd', serialize($account));
			$_SESSION["logged_on_user"] = "";
			header('Location: index.php');
		}
		else
			echo "Tiens tiens tiens vous essayez de supprimer un autre utilisateur";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Suppression de compte</title>
</head>
<body>
	<form action="delete_user.php" method="POST">
		Identifiant: <input type="text" name="login" value="" />
		<br />
		Mot de passe: <input type="password" name="passwd" value="" />
		<br />
		<input type="submit" name="submit" value="OK" />
		<br />
		<a href="index.php">Page d'accueil</a>
	</form>
</body>
</html>

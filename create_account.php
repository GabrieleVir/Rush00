<?php
	session_start();

	if ($_POST['login'] && $_POST['passwd'] && ($_POST['admin'] == "0" || $_POST['admin'] == "1") && $_POST['submit'] && $_POST['submit'] === "OK") {
		$account = unserialize(file_get_contents('./private/passwd'));
		$check = 0;
		if ($account) {
			foreach ($account as $k => $v) {
				if ($v['login'] === $_POST['login'])
					$check = 1;
			}
		}
		if ($check) {
			echo "Login déjà utilisé";
		}
		else {
			if ($_POST['admin'] == "1" || $_POST['admin'] == "0") {
				$tmp['login'] = $_POST['login'];
				$tmp['passwd'] = hash('whirlpool', $_POST['passwd']);
				$tmp['admin'] = $_POST['admin'];
				$account[] = $tmp;
				file_put_contents('./private/passwd', serialize($account));
				header('Location: create_account2.php');
			}
			else
				echo "Mauvaise choix d'admin (1 = oui ou 0 = non)";
		}
	}
?>

<!DOCTYPE html>
<html><head>
	<meta charset="utf-8">
	<title>Créer un utilisateur</title>
</head>
<body>
	<form action="create_account.php" method="POST">
		Identifiant : <input type="text" name="login" value="" />
		<br />
		Mot de Passe : <input type="password" name="passwd" value="" />
		<br />
		Admin ? (0 si non): <input type="text" name="admin" value="" />
		<br />
		<input type="submit" name="submit" value="OK" />
		<br />
		<a href="index.php">Page d'accueil</a>
	</form>
</body>
</html>

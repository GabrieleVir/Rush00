<?php
	if ($_POST['login'] && $_POST['passwd'] && $S_POST['submit'] && $_POST['submit'] === "OK") {
		if (!file_exists('../private')) {
			mkdir('../private');
		}
		if (!file_exists('../private/passwd')) {
			file_put_contents('../private/passwd', null);
		}
		$account = unserialize(file_get_contents('../private/passwd'));
		$check = 0;
		if ($account) {
			foreach ($account as $k => $v) {
				if ($v['login'] === $_POST['login'])
					$check = 1;
			}
		}
		if ($check) {
			echo "Login already used\n";
		}
		else {
			$tmp['login'] = $_POST['login'];
			$tmp['passwd'] = hash('whirlpool', $_POST['passwd']);
			$account[] = $tmp;
			file_put_contents('../private/passwd', serialize($account));
			header('Location: create_account2.html');
		}
	}
	else {
		header('Location: create_account.html');
		echo "ERROR\n";
	}
?>

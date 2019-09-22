<?php
	session_start();

	function auth_user($id, $pwd) {
		$account = unserialize(file_get_contents('./private/passwd'));
		if ($account) {
			foreach ($account as $k => $v) {
				if ($v['login'] === $id && $v['passwd'] === hash('whirlpool', $pwd))
					return true;
			}
		}
		return false;
	}
?>

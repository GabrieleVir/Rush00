<?php
	session_start();

function is_in_csv($path_db, $elem_verif, $column)
{
	$fp = fopen($path_db, "r");
	if (flock($fp, LOCK_SH)) {
		$file_data = unserialize(file_get_contents($path_db));
		var_dump($file_data);
		foreach ($file_data as $elem) {
			if ($elem[$column] == $elem_verif)
			{
				flock($fp, LOCK_UN);
				fclose($fp);
				return (TRUE);
			}
		}
	}
	else
	{
		echo "Error flock\n";
		return (FALSE);
	}
	flock($fp, LOCK_UN);
	fclose($fp);
	return (FALSE);
}
?>

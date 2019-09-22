<?php

    function is_in_csv($path_db, $elem_verif, $column)
    {
        $fp = fopen($path_db, "r");
        if (flock($fp, LOCK_SH)) {
            $file_data = unserialize(file_get_contents($path_db));
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
            fclose($fp);
            return (FALSE);
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return (FALSE);
    }

    function add_in_csv($path_db, $serial_elem)
    {
        $fp = fopen($path_db, "r+");
        if (flock($fp, LOCK_EX)) {
            file_put_contents($path_db, $serial_elem);
        }
        else
        {
            echo "Error flock\n";
            return (FALSE);
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return (TRUE);
    }

    if (file_exists("./private/categories") == false)
        file_put_contents("./private/categories", serialize(array()));
	if ($_POST && $_POST['name'] && $_POST['submit'] && $_POST['submit'] === "OK") {
		$categories = unserialize(file_get_contents('./private/categories'));
        $check = 0;
        if (is_in_csv("./private/categories", $_POST['name'], 'name')) {
            $check = 1;
		}
		if ($check) {
			echo "Category already exist\n";
        }
        else {
            if (!preg_match('/[^A-Za-z]/', $_POST['name'])){
                $categories[] = [
                    'name' => $_POST['name'],
                ];
                add_in_csv('./private/categories', serialize($categories));
                echo "PRODUCT CREATED\n";
            }
            else 
                echo "Category name not well formatted\n";
        }
	}
?>

<form method="POST" action="create_category.php">
    Category name: <input type="text" name="name">
    <input type ="submit" name="submit" value="OK">
</form>

<a href="index.php">Page d'accueil</a>

<a href="create_product.php"> Create products </a>
<?php

    function checkbox_categories()
    {
        $fp = fopen("./private/categories", "r");
        if (flock($fp, LOCK_SH))
        {
            $categories = unserialize(file_get_contents("./private/categories"));
            foreach ($categories as $category)
            {
?>
                <input type="checkbox" name="checkbox[]" value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?><br>
<?php
            }
            flock($fp, LOCK_UN);
        }
        else {
            fclose($fp);
            return (NULL);
        }
        fclose($fp);
    }

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
            return (FALSE);
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return (FALSE);
    }

    function fill_categories()
    {
        $categories = [];
        foreach ($_POST["checkbox"] as $category_name)
        {
            if (!is_in_csv("./private/categories", $category_name, 'name'))
                return (NULL);
            $categories[] = $category_name;
        }
        return ($categories);
    }



    function add_in_csv($path_db, $serial_elem)
    {
        $fp = fopen($path_db, "r");
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

    if (file_exists("./private/products") == false)
        file_put_contents("./private/products", serialize(array()));
	if ($_POST['title'] && $_POST['price'] && $_POST['submit'] && $_POST['submit'] === "OK") {
		$product = unserialize(file_get_contents('./private/products'));
		$check = 0;
		if ($product) {
			foreach ($product as $k => $v) {
				if ($v['name'] === $_POST['title'])
					$check = 1;
			}
		}
		if ($check) {
			echo "Product already exist";
        }
        else {
            var_dump($_FILES);
            if (!preg_match('/[^A-Za-z0-9]/', $_POST['title']) && !preg_match('/[^0-9]/', $_POST['price']) && isset($_FILES['img']) && !empty($_FILES['img']['name'])){
                $categories = fill_categories();
                $resultat = move_uploaded_file($_FILES['img']['tmp_name'], "./private/img/".$_FILES['img']['name']);
                if (!$categories || !$resultat)
                {
                    echo "Categories invalid\n";
                    exit ;
                }
                $product[] = [
                    'name' => $_POST['title'],
                    'price' => $_POST['price'],
                    'img' => $_FILES['img']['name'],
                    'categories' =>  $categories
                ];
                add_in_csv('./private/products', serialize($product));
                echo "PRODUCT CREATED";
            }
            else 
                echo "Input not well formatted\n";
        }
	}
?>

<form method="POST" action="create_product.php" enctype="multipart/form-data">
    Nom du produit: <input type="text" name="title"><br />
    Prix: <input type="text" name="price"><br />
    Fichier image: <input type="file" name="img"><br />
    <?php echo checkbox_categories(); ?>
    <input type ="submit" name="submit" value="OK">
</form>
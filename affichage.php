<?php
    session_start();

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

    function display_categories()
    {
        global $curr_cat;

        $fp = fopen("./private/categories", "r");
        if (flock($fp, LOCK_SH))
        {
            $i = 0;
            $categories = unserialize(file_get_contents("./private/categories"));
            foreach ($categories as $category)
            {
                if ($i == 0)
                    $curr_cat = $category['name'];
    ?>
                <option name="<?php echo $category['name']; ?>" value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
    <?php
                $i++;
            }
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        else {
            return (NULL);
        }
    }



?>

<html>
    <head>
		<meta charset="utf-8">
        <title>Bromozon Eshop</title>
        <style>
            .produits{
                display:flex;
                justify-content: space-evenly;
                flex-wrap: wrap;
            }
            .produit{
                border:2px solid black;
                width: 25%;
                height: 250px;
                text-align: center;
            }
            .img_prod{
                width:100%;
                height:60%;
            }
            .cate {
                width:100%;
                height: 50px;
            }
            .produit p {
                margin: 5px;
            }
            .produit form input {
                width: 100%;
            }
            .cate {
                text-align: center;
            }
            .cat_h1 {
                text-align: center;
            }
        </style>
    </head>
	<body>
		<header class="cate">
            <form method="POST" action="affichage.php">
                <select name="categories" value="categories">
                    <?php echo display_categories(); ?>
                    <input type="submit" value="Search" name="submit">
                </select>
            </form>
        </header>
        <h1 class="cat_h1">
                    <?php
                        if (isset($_POST["submit"]))
                        {
                            if ($_POST["submit"] == "Search" && $_POST["categories"])
                            {
                                $_SESSION['first_cat'] = $_POST["categories"];
                            }
                        }
                        else
                            $_SESSION['first_cat'] = $curr_cat; 
                        echo "Category: " . $_SESSION['first_cat'];
                    ?>
                </h1>
        <section class="produits">
        <?php

            
            if (is_in_csv("./private/categories", $_SESSION['first_cat'], 'name'))
            {
                $fp = fopen("./private/products", 'r');
                if (flock($fp, LOCK_SH))
                {
                    $allproducts = unserialize(file_get_contents("./private/products"));
                    foreach ($allproducts as $product)
                    {
                        foreach ($product['categories'] as $cat_name)
                        {
                            if ($_SESSION['first_cat'] == $cat_name)
                            {

        ?>
            <div class="produit">
                <form method="POST" action="panier.php">
                    <img src="./private/img/<?php echo $product['img']; ?>" name="img" class="img_prod">
                    <p name="<?php echo $product['name']; ?>">Title: <?php echo $product['name']; ?></p>
                    <p name="price">Price: <?php echo $product['price']; ?>€</p>
                    <input type="submit" value="Acheter" name="submit">
                </form>
            </div>
            <?php
                            }
                        }
                    }
                }
            }
            ?>
        </section>
	</body>
</html>
<?php
session_start();

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
        fclose($fp);
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

if ($_SESSION['admin'] == '1')
{
    if (isset($_POST) && $_POST['oldname'] != "" && $_POST['submit'] == "OK")
    {
        if (is_in_csv("./private/products", $_POST['oldname'], 'name'))
        {
                $product = unserialize(file_get_contents("./private/products"));
                $resultat = move_uploaded_file($_FILES['img']['tmp_name'], "./private/img/".$_FILES['img']['name']);
                $i = 0;
                foreach ($product as $elem) {
                    if ($elem['name'] == $_POST['oldname'])
                        break ;
                    $i++;
                }
                array_splice($product, $i, 1);
                $categories = fill_categories();
                $product[] = [
                    'name' => $_POST['title'],
                    'price' => $_POST['price'],
                    'img' => $_FILES['img']['name'],
                    'categories' =>  $categories
                ];
                add_in_csv('./private/products', serialize($product));
                echo "DONE\n";
                exit ;
            }
        }
        else
            echo "IL FAUT REMPLIR TOUS LES CHAMPS MWHAHAHAHA\n";
}
?>

<form method="POST" action="modif_product.php" enctype="multipart/form-data">
    Ancien nom du produit : <input type="text" name="oldname"><br />
    Nouveau nom du produit : <input type="text" name="title"><br />
    Nouveau Prix: <input type="text" name="price"><br />
    Nouveau Fichier image: <input type="file" name="img"><br />
    <?php echo checkbox_categories(); ?>
    <input type ="submit" name="submit" value="OK">
</form>

<a href="index.php">Page d'accueil</a>
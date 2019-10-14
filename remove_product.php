<?php
session_start();

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

if ($_SESSION['admin'] == '1')
{
    if (isset($_POST) && $_POST['oldname'] != "" && $_POST['submit'] == "OK")
    {
        if (is_in_csv("./private/products", $_POST['oldname'], 'name'))
        {
                $product = unserialize(file_get_contents("./private/products"));
                $i = 0;
                foreach ($product as $elem) {
                    if ($elem['name'] == $_POST['oldname'])
                        break ;
                    $i++;
                }
                array_splice($product, $i, 1);
                add_in_csv('./private/products', serialize($product));
                echo "DONE\n";
                exit ;
            }
        }
        else
            echo "IL FAUT REMPLIR TOUS LES CHAMPS MWHAHAHAHA\n";
}
?>


<form method="POST" action="remove_product.php">
    Product name : <input type="text" name="oldname">
    <input type ="submit" name="submit" value="OK">
</form>
<a href="index.php">Page d'accueil</a>
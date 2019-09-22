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

if ($_SESSION['admin'] == '1')
{
    if (isset($_POST) && $_POST['oldname'] && $_POST['submit'] == "OK")
    {
        if (is_in_csv("./private/categories", $_POST['oldname'], 'name'))
        {
            $fp = fopen("./private/categories", "r+");
            if (flock($fp, LOCK_EX))
            {
                $categories = unserialize(file_get_contents("./private/categories"));
                $i = 0;
                foreach ($categories as $elem) {
                    if ($elem['name'] == $_POST['name'])
                        break ;
                    $i++;
                }
                array_splice($categories, $i, 1);
                $categories[] = [
                    'name' => $_POST['name'],
                ];
                add_in_csv('./private/categories', serialize($categories));
                flock($fp, LOCK_UN);
                fclose($fp);
                echo "DONE\n";
            }
        }
        echo "IL FAUT REMPLIR TOUS LES CHAMPS MWHAHAHAHA\n";
    }
}
?>


<form method="POST" action="modif_category.php">
    Old Category name: <input type="text" name="oldname">
    New Category name: <input type="text" name="name">
    <input type ="submit" name="submit" value="OK">
</form>

<a href="index.php">Page d'accueil</a>

<a href="create_product.php"> Create products </a>

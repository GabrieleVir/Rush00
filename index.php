<?php
session_start();

if (!file_exists('./private')) {
	mkdir('./private');
}
if (!file_exists('./private/passwd')) {
	file_put_contents('./private/passwd', null);
}
?>
<!DOCTYPE html>
<html><head>
		<meta charset="utf-8">
		<title>Humblemerce</title>
		<style>
			.products{
				position: relative;
				top: 5%;
				width: 100%;
				height: 750px;
			}
			.flex {
				display: flex;
				justify-content: space-evenly;
				flex-wrap: wrap;
			}
		</style>
</head>
<body>
	<header class="flex ">
		Salut tu veux tester quoi ? <br />
		<?php		
			if (!isset($_SESSION['logged_on_user']) || $_SESSION['logged_on_user'] == "")
			{
		?>
			<a href="login.php">Login</a><br />
			<a href="create_account.php">Create</a><br />
		<?php
			}
		?>

		<?php		
			if (isset($_SESSION['logged_on_user']) && $_SESSION['logged_on_user'] != "")
			{
		?>
		<p> Welcome <?php echo $_SESSION['logged_on_user']; ?></p>
		<a href="delete_user.php">Delete your account</a>

		<?php
			}
		?>
		<?php
			if (isset($_SESSION['admin']) && $_SESSION['admin'] == '1')
			{
		?>
			<a href="create_product.php">Add product</a>
			<a href="create_category.php">Add category</a>
			<a href="delete_user.php">Delete users</a>
			<a href="remove_product.php">Delete product</a>
			<a href="remove_category.php">Delete category</a>
			<a href="modif_product.php">Modif product</a>
			<a href="modif_category.php">Modif category</a>
		<?php
			}
		?>
	</header>
	<section>
		<iframe class="products" src="affichage.php">
		<iframe src="panier.php">
	</section>
</body>
</html>

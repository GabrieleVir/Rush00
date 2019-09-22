<?php
session_start();

if (!file_exists('./private')) {
	mkdir('./private');
}
if (!file_exists('./private/passwd')) {
	file_put_contents('./private/passwd', null);
}
var_dump($_SESSION);
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
			}
		</style>
</head>
<body>
	<header class="flex ">
		Salut tu veux tester quoi ? <br />
		<?php		
			if (!isset($_SESSION['logged_on_user']))
			{
		?>
			<a href="login.php">Login</a><br />
			<a href="create_account.php">Create</a><br />
		<?php
			}
		?>

		<?php		
			if (isset($_SESSION['logged_on_user']))
			{
		?>
		<a href="delete_user.php">Delete</a>
		<p> Welcome <?php echo $_SESSION['logged_on_user']; ?></p>

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

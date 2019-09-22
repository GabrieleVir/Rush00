<?php
	if (!file_exists('./private')) {
		mkdir('./private');
	}
	if (!file_exists('./private/passwd')) {
		file_put_contents('./private/passwd', null);
	}
	if (!file_exists('./private/categories')) {
		file_put_contents('./private/categories', null);
	}
	if (!file_exists('./private/products')) {
		file_put_contents('./private/products', null);
	}
?>

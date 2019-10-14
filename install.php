<?php
	if (!file_exists('./private')) {
		mkdir('./private', 0755, true);
	}
	if (!file_exists('./private/img')) {
		mkdir('./private/img', 0755, true);
	}
	if (!file_exists('./private/passwd')) {
		file_put_contents('./private/passwd', serialized(array()));
	}
	if (!file_exists('./private/categories')) {
		file_put_contents('./private/categories', serialized(array()));
	}
	if (!file_exists('./private/products')) {
		file_put_contents('./private/products', serialized(array()));
	}
?>

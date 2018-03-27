<?php

	define("DB_HOST", "localhost");
	define("DB_LOGIN", "root");
	define("DB_PASSWORD", "paoly007");
	define("DB_DEFAULT_NAME","test");
	define("TABLE_USER", "all_user");
	define("TABLE_COLLECT", "all_collect");
	try {
		$db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DEFAULT_NAME,DB_LOGIN,DB_PASSWORD);
	} catch (Exception $e) {
		
	}
?>
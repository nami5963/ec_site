<?php

function dbConnect() {
	define('DSN', 'mysql:host=localhost;dbname=ec_site');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'glad');	

	try{
		$db = new PDO(DSN, DB_USER, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	}catch(PDOException $e){
		$e->getMessage();
		exit();
	}
}

function escape($str) {
	return htmlspecialchars($str, ENT_QUOTES);
}

?>

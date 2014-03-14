<?php

define('DB_HOST', '{$dbHost}');
define('DB_NAME', '{$dbName}');
define('DB_USER', '{$dbUser}');
define('DB_PASS', '{$dbPass}');

define('TABLE_NAME', '{$trainingResultTable}');

ini_set('max_execution_time', '{$maxExecutionTime}');
ini_set('memory_limit', '{$memoryLimit}');

/**
 * @param string   $folder
 * @param string   $args
 * @param callable $function
 */
function execute($folder, $args, Closure $function)
{
	try {
		$args = unserialize($args);

		$result = $function($args);
		$result = serialize($result);

		$pdo   = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		$query = "INSERT INTO `" . TABLE_NAME . "` (`folder`, `result`)
        VALUES ('$folder', '$result');";
		$pdo->query($query);
	}
	catch (Exception $e) {
		die();
	}
}
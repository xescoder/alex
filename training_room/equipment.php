<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'alex');
define('DB_USER', 'root');
define('DB_PASS', '');

define('TABLE_NAME', 'training_result');

/**
 * @param string $functionName
 * @param string $args
 * @param Closure $function
 * @param string $folder
 */
function execute($functionName, $args, $function, $folder)
{
    $args = unserialize($args);

	$result = $function($args);
	$result = serialize($result);

	try {
		$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		$query =  "INSERT INTO `" . TABLE_NAME . "` (`folder`, `function`, `result`)
        VALUES ('$folder', '$functionName', '$result');";
		$db->query($query);
	} catch (Exception $e) {
		die();
	}
}
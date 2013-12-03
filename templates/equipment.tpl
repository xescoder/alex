<?php

define('DB_HOST', '{$dbHost}');
define('DB_NAME', '{$dbName}');
define('DB_USER', '{$dbUser}');
define('DB_PASS', '{$dbPass}');

define('TABLE_NAME', 'training_result');

/**
 * @param Closure $function
 * @param mixed   $args
 * @param string  $folder
 * @param string  $functionName
 */
function execute($function, $args, $folder, $functionName)
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
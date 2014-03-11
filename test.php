<?php
include 'alex.php';

use \Alex\Internal\Trainer;

$config  = new AlexConfig();
$trainer = new Trainer($config);

$trainer->train(123, function ($res) {
	if ($res == 123) return 5;
	else return 0;
});
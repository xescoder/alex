<?php
include 'alex.php';

use \Alex\Internal\Trainer;

$config  = new AlexConfig();
$trainer = new Trainer($config);

$trainer->train(123, function ($func) {
	if ($func(1) != 1) return 0;
	if ($func(2) != 2) return 1;
	if ($func(3) != 3) return 2;
	return 3;
});
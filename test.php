<?php
include 'alex.php';

$alex = new Alex();

$alex->train('test', 1, function ($func) {
	$diff = 0;

	for ($i = 0; $i < 10; $i ++) {
		$res     = $func($i);
		$correct = $i ^ 2 - 5;
		$diff += abs($res - $correct);
	}

	return 1000000 - $diff;
});

var_dump($alex->execute('test', 1));
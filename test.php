<?php
include 'alex.php';

$alex = new Alex();

$alex->train('test', 123, function ($func) {
	if ($func(1) != 1) return 0;
	if ($func(2) != 2) return 1;
	if ($func(3) != 3) return 2;
	return 3;
});
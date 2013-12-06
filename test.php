<?php
include 'alex.php';

use \Alex\Internal\Trainer,
	\Alex\Internal\Trainee;

$config = new AlexConfig();
$trainer = new Trainer($config);

//$trainer->prepareTrainingRoom();
//
//for($i = 0; $i < 10; $i++) {
//	$trainee = new Trainee('test', $i);
//	$trainee->create();
//	$trainee->train($config->trainingFolder);
//}
//
//sleep(5);
//
//var_dump($trainer->getResults(function($res){
//	return $res * $res;
//}));

$result = [
	0 => 0,
	2 => 2,
	5 => 5,
	6 => 6,
	7 => 7,
	15 => 15,
	9 => 9,
];

var_dump($trainer->roulette($result, 5));

<?php
include 'alex.php';

use \Alex\Internal\Trainer,
	\Alex\Internal\Trainee;

$config  = new AlexConfig();
$trainer = new Trainer($config);

//$trainer->prepareTrainingRoom();
//
//for($i = 0; $i < 10; $i++) {
//	$trainee = new Trainee($i);
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
	'/test1/' => 0,
	'/test2/' => 2,
	'/test3/' => 5,
	'/test4/' => 6,
	'/test5/' => 7,
	'/test6/' => 15,
	'/test7/' => 9,
];

var_dump($trainer->roulette($result, 10));

//$trainees = $trainer->getResults(function($res){
//	return $res;
//});
//
//$trainer->copyInBest($trainees);
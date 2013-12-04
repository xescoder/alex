<?php
include 'alex.php';

use \Alex\Internal\Trainer;

$config = new AlexConfig();
$trainer = new Trainer($config);

$trainer->prepareTrainingRoom();

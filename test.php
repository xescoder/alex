<?php
include 'alex.php';

use \Alex\Internal\Trainee;

$config = new AlexConfig();

// first
$trainee = new Trainee('first', null);
$trainee->create();
$trainee->train($config->trainingFolder);

// inherit
$trainee = new Trainee('inherit', null);
$trainee->inherit($config->trainingFolder . '/123', $config->mutate);
$trainee->train($config->trainingFolder);

$args = unserialize($args);
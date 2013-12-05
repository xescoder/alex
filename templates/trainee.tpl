<?php
include __DIR__ . '/../equipment.php';

execute('{$functionName}', '{$args}', function($args){
	return include 'body.php';
}, __DIR__);
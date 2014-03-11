<?php
include __DIR__ . '/../equipment.php';

execute('{$args}', function($args){
	return include 'body.php';
}, __DIR__);
<?php
include __DIR__ . '/../equipment.php';

execute(__DIR__, '{$args}', function($args){
	return include 'body.php';
});
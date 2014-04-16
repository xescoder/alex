<?php
include __DIR__ . '/../equipment.php';

execute(__DIR__, '{$args}', function($a){
	return include 'body.php';
});
<?php
include __DIR__ . '/../equipment.php';

execute(function($args){
	return include 'body.php';
}, [1,2,3], __DIR__, 'test');
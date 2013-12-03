<?php
include __DIR__ . '/../equipment.php';

execute(function($args){
	return include 'body.php';
}, {$args}, __DIR__, {$functionName});
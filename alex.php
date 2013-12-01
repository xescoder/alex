<?php

spl_autoload_register(function ($class) {
	if (preg_match('/Alex\\\\(.*)\\\\([^\\\\]+)/', $class, $matches)) {
		include 'classes/' . strtolower($matches[1]) . '/' . $matches[2] . '.php';
		return;
	}

	include 'classes/' . $class . '.php';
});
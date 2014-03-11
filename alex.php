<?php

spl_autoload_register(function ($class) {
	if (preg_match('/Alex\\\\(.*)\\\\([^\\\\]+)/', $class, $matches)) {
		$path = 'classes/' . strtolower($matches[1]) . '/' . $matches[2] . '.php';
		if (file_exists($path)) {
			require_once $path;
			return TRUE;
		}
	}

	if (preg_match('/Alex[^\\\\]*/', $class, $matches)) {
		$path = 'classes/' . $class . '.php';
		if (file_exists($path)) {
			require_once $path;
			return TRUE;
		}
	}
});
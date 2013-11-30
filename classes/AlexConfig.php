<?php

/**
 * Class AlexConfig
 *
 * @property string  $dbHost
 * @property string  $dbName
 * @property string  $dbUser
 * @property string  $dbPass
 * @property int     $maxTrainingTime
 * @property Closure $mutate
 */
class AlexConfig
{
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	private $maxTrainingTime;

	private $mutate;

	public function __get($property)
	{
		$props = get_class_vars(self);
		if (in_array($property, $props)) {
			return $this->$property;
		}
	}

	public function __set($property, $value)
	{
		$props = get_class_vars(self);
		if (in_array($property, $props)) {
			$this->$property = $value;
		}
	}
} 
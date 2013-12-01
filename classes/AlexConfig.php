<?php

/**
 * Class AlexConfig
 *
 * @property string  $dbHost
 * @property string  $dbName
 * @property string  $dbUser
 * @property string  $dbPass
 * @property string  $trainingFolder
 * @property int     $maxTrainingTime
 * @property Closure $mutate
 */
class AlexConfig
{
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	private $trainingFolder;
	private $maxTrainingTime;

	private $mutate;

	public function __construct()
	{
		$this->dbHost = 'localhost';
		$this->dbName = 'alex';
		$this->dbUser = 'root';
		$this->dbPass = '';

		$this->trainingFolder  = 'training_room';
		$this->maxTrainingTime = 1;

		$this->mutate = function ($source) {
			return AlexMutation::init()->mutate($source);
		};
	}

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
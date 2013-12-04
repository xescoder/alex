<?php
use Alex\Internal\Equipment;

/**
 * Class AlexConfig
 *
 * @property string  $dbHost
 * @property string  $dbName
 * @property string  $dbUser
 * @property string  $dbPass
 * @property string  $trainingFolder
 * @property int     $trainingCycles
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
	private $trainingCycles;
	private $maxTrainingTime;

	private $mutate;

	public function __construct()
	{
		$this->dbHost = '127.0.0.1';
		$this->dbName = 'alex';
		$this->dbUser = 'root';
		$this->dbPass = '';

		$this->trainingFolder  = __DIR__ . '/../training_room';
		$this->trainingCycles  = 1000;
		$this->maxTrainingTime = 1;

		$this->mutate = function ($source) {
			return AlexMutation::init()->mutate($source);
		};
	}

	public function __get($property)
	{
		$props = get_object_vars($this);
		if (array_key_exists($property, $props)) {
			return $this->{$property};
		}
	}

	public function __set($property, $value)
	{
		$props = get_object_vars($this);
		if (array_key_exists($property, $props)) {
			$this->{$property} = $value;
		}
	}
} 
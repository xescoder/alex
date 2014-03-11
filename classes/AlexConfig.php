<?php

/**
 * Class AlexConfig
 *
 * @property string   $dbHost
 * @property string   $dbName
 * @property string   $dbUser
 * @property string   $dbPass
 *
 * @property string   $trainingResultTable
 *
 * @property string   $bestFolder
 * @property string   $trainingFolder
 * @property int      $trainingCycles
 * @property int      $maxTrainingTime
 *
 * @property callable $mutate
 */
class AlexConfig
{
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	private $trainingResultTable;

	private $bestFolder;
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

		$this->trainingResultTable = 'training_result';

		$this->bestFolder      = __DIR__ . '/../best';
		$this->trainingFolder  = __DIR__ . '/../training_room';
		$this->trainingCycles  = 120;
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
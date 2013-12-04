<?php
namespace Alex\Internal;

use \PDO, \AlexConfig;

/**
 * Class Trainer
 *
 * @property AlexConfig $config
 *
 * @package Alex\Internal
 */
class Trainer
{
	/** @var  AlexConfig */
	private $config;

	/**
	 * @param AlexConfig $config
	 */
	public function __construct(AlexConfig $config)
	{
		$this->config = $config;
	}

	private function clearResults()
	{
		$tableName = 'training_result';

		$db    = new \PDO(
			'mysql:host=' . $this->config->dbHost . ';dbname=' . $this->config->dbName,
			$this->config->dbUser,
			$this->config->dbPass
		);

		$query = 'DELETE FROM `' . $tableName . '`;';
		$db->query($query);
	}

	private function deleteDir($dir, $withRoot = true)
	{
		if (!is_dir($dir) || is_link($dir)) {
			return unlink($dir);
		}

		foreach (scandir($dir) as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}

			if (!$this->deleteDir($dir . '/' . $file)) {
				chmod($dir . '/' . $file, 0777);
				if (!$this->deleteDir($dir . '/' . $file)) {
					return false;
				}
			};
		}

		if ($withRoot) {
			return rmdir($dir);
		}

		return true;
	}

	private function clearTrainingRoom()
	{
		$this->deleteDir($this->config->trainingFolder, false);
	}

	public function prepareTrainingRoom()
	{
		$this->clearResults();
		$this->clearTrainingRoom();

		$equipment = new Equipment(
			$this->config->dbHost,
			$this->config->dbName,
			$this->config->dbUser,
			$this->config->dbPass,
			$this->config->trainingFolder
		);

		$equipment->save();
	}

	public function __get($property)
	{
		switch ($property) {
			case 'config':
				return $this->config;
		}
	}

	public function __set($property, $value)
	{
		switch ($property) {
			case 'config':
				$this->config = $value;
				break;
		}
	}
} 
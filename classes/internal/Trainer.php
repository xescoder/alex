<?php

namespace Alex\Internal;

use \PDO,
	\AlexConfig;

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

	/**
	 * @return string
	 */
	private function getTrainingResultTableName()
	{
		return 'training_result';
	}

	/**
	 * @return PDO
	 */
	private function getPDO()
	{
		$pdo = new \PDO(
			'mysql:host=' . $this->config->dbHost . ';dbname=' . $this->config->dbName,
			$this->config->dbUser,
			$this->config->dbPass
		);

		return $pdo;
	}

	private function clearResults()
	{
		$pdo   = $this->getPDO();
		$query = 'DELETE FROM `' . $this->getTrainingResultTableName() . '`;';
		$pdo->query($query);
	}

	private function deleteDir($dir, $withRoot = TRUE)
	{
		if (! is_dir($dir) || is_link($dir)) {
			return unlink($dir);
		}

		foreach (scandir($dir) as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}

			if (! $this->deleteDir($dir . '/' . $file)) {
				chmod($dir . '/' . $file, 0777);
				if (! $this->deleteDir($dir . '/' . $file)) {
					return FALSE;
				}
			};
		}

		if ($withRoot) {
			return rmdir($dir);
		}

		return TRUE;
	}

	private function clearTrainingRoom()
	{
		$this->deleteDir($this->config->trainingFolder, FALSE);
	}

	public function copyInBest($trainees)
	{
		$this->deleteDir($this->config->bestFolder, FALSE);

		$number = 1;
		$result = [];
		foreach ($trainees as $traineeFolder) {
			$path = $this->config->bestFolder . '/' . $number . '.php';
			copy($traineeFolder . '/body.php', $path);
			$result[] = $path;
			$number ++;
		}

		return $result;
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

	/**
	 * @param string $estimate
	 *
	 * @return array
	 */
	public function getResults($estimate)
	{
		$tableName = $this->getTrainingResultTableName();
		$pdo       = $this->getPDO();

		$query = 'SELECT * FROM `' . $tableName . '`;';

		$cursor = $pdo->query($query);
		$cursor->setFetchMode(PDO::FETCH_ASSOC);

		$result = [];
		while ($row = $cursor->fetch()) {
			$res          = unserialize($row['result']);
			$res          = $estimate($res);
			$result[$res] = $row['folder'];
		}

		krsort($result);

		return $result;
	}

	public function roulette($trainees, $max_count)
	{
		$sum = 0;
		foreach ($trainees as $est => $folder) {
			$sum += $est;
		}

		$next = 0;
		$arr  = [];
		foreach ($trainees as $est => $folder) {
			$next += ($est / $sum) * 1000;
			$arr[$next] = $folder;
		}

		$result = [];
		for ($i = 0; $i < $max_count; $i ++) {
			$rand = rand(0, 1000);
			foreach ($arr as $max => $folder) {
				if ($max > $rand) {
					$result[] = $folder;
					break;
				}
			}
		}

		return $result;
	}

	public function train($operationName, $args, $estimate)
	{
		$first     = TRUE;
		$max_count = 10;
		$best      = [];

		for ($i = 0; $i < $this->config->trainingCycles; $i ++) {
			$this->prepareTrainingRoom();

			for ($j = 0; $j < $max_count; $j ++) {
				$trainee = new Trainee($operationName, $args);
				if ($first) {
					for ($k = 0; $k < 5; $k ++) {
						$trainee->create();
					}
				}
				else {
					for ($k = 0; $k < 5; $k ++) {
						$trainee->inherit($best[$j], $estimate);
					}
				}
				$trainee->train($this->config->trainingFolder);
			}

			sleep($this->config->maxTrainingTime);

			$result = $this->getResults($estimate);
			$result = $this->roulette($result, $max_count);

			$best = $this->copyInBest($result);
		}
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
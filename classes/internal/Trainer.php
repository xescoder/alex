<?php

namespace Alex\Internal;

use \PDO,
	\Closure,
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
		$table = $this->config->trainingResultTable;
		$pdo   = $this->getPDO();

		$query = 'DROP TABLE IF EXISTS `' . $table . '`;';
		$pdo->query($query);

		$query = "CREATE TABLE `" . $table . "` (
				  `folder` char(255) NOT NULL DEFAULT ''
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$pdo->query($query);
	}

	/**
	 * @param string $dir
	 * @param bool   $withRoot
	 * @return bool
	 */
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

	/**
	 * @param array $trainees
	 * @return array
	 */
	private function copyInBest($trainees)
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

	private function prepareTrainingRoom()
	{
		$this->clearResults();
		$this->clearTrainingRoom();

		$equipment = new Equipment($this->config);
		$equipment->save();
	}

	/**
	 * @return string[]
	 */
	private function getSurvivors()
	{
		$table = $this->config->trainingResultTable;
		$pdo   = $this->getPDO();

		$query = 'SELECT `folder` FROM `' . $table . '`;';

		$cursor = $pdo->query($query);
		return $cursor->fetchAll(PDO::FETCH_COLUMN, 0);
	}

	/**
	 * @param callable $estimate
	 * @return array
	 */
	private function getResults(Closure $estimate)
	{
		$survivors = $this->getSurvivors();

		$result = [];
		foreach ($survivors as $survivor) {
			$result[$survivor] = $estimate(function ($args) use ($survivor) {
				return include $survivor . '/body.php';
			});
		}

		arsort($result);

		return $result;
	}

	/**
	 * @param array $trainees
	 * @param int   $max_count
	 * @return array
	 */
	private function roulette($trainees, $max_count)
	{
		if (! count($trainees)) {
			return [];
		}

		$sum = 0;
		foreach ($trainees as $est) {
			$sum += $est;
		}

		if ($sum == 0) {
			$result = [];
			$count  = 0;
			$max    = count($trainees) > $max_count ? $max_count : count($trainees);

			foreach ($trainees as $folder => $est) {
				$result[] = $folder;

				$count ++;
				if ($count > $max) {
					break;
				}
			}

			return $result;
		}

		$next = 0;
		$arr  = [];
		foreach ($trainees as $folder => $est) {
			$next += ($est / $sum) * 1000;
			$arr[$folder] = $next;
		}

		$result = [];
		for ($i = 0; $i < $max_count; $i ++) {
			$rand = rand(0, 1000);
			foreach ($arr as $folder => $max) {
				if ($max > $rand) {
					$result[] = $folder;
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * @param string $functionName
	 * @param string $folder
	 */
	private function createFunction($functionName, $folder)
	{
		$functionBody = file_get_contents($folder . '/body.php');
		$functionBody = preg_replace('/\<\?php\s/', '', $functionBody);
		$functionBody = preg_replace('/(\n)(.*)/', '$1	$2', $functionBody);

		$function = file_get_contents(__DIR__ . '/../../templates/function.tpl');

		$function = str_replace('{$functionName}', $functionName, $function);
		$function = str_replace('{$functionBody}', $functionBody, $function);

		$f = fopen($this->config->functionsFolder . '/' . $functionName . '.php', 'w');
		fwrite($f, $function);
		fclose($f);
	}

	/**
	 * @param string   $functionName
	 * @param mixed    $args
	 * @param callable $estimate
	 */
	public function train($functionName, $args, Closure $estimate)
	{
		$trainingCycles        = $this->config->trainingCycles;
		$countInBestFolder     = $this->config->countInBestFolder;
		$countInTrainingFolder = $this->config->countInTrainingFolder;

		$trainingFolder = $this->config->trainingFolder;
		$mutate         = $this->config->mutate;

		$best      = [];
		$bestIndex = 0;

		$superior = NULL;

		$trainee = new Trainee($args);

		for ($i = 0; $i < $trainingCycles; $i ++) {
			$this->prepareTrainingRoom();

			for ($j = 0; $j < $countInTrainingFolder; $j ++) {
				if (count($best) > 0) {
					$trainee->inherit($best[$bestIndex]);

					$bestIndex ++;
					$bestIndex = $bestIndex < count($best) ? $bestIndex : 0;
				}

				$trainee->train($trainingFolder, $mutate);
			}

			sleep($this->config->maxTrainingTime);

			$result = $this->getResults($estimate);

			foreach ($result as $folder => $est) {
				$superior = $folder;
				break;
			}

			$result = $this->roulette($result, $countInBestFolder);
			$best   = $this->copyInBest($result);
		}

		if ($superior) {
			$this->createFunction($functionName, $superior);
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
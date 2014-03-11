<?php

namespace Alex\Internal;

use \AlexConfig;

/**
 * Class Equipment
 *
 * @package Alex\Internal
 */
class Equipment
{
	/** @var AlexConfig */
	private $config;

	/**
	 * @param AlexConfig $config
	 */
	public function __construct($config)
	{
		$this->config = $config;
	}

	public function save()
	{
		$code = file_get_contents(__DIR__ . '/../../templates/equipment.tpl');

		$code = str_replace('{$dbHost}', $this->config->dbHost, $code);
		$code = str_replace('{$dbName}', $this->config->dbName, $code);
		$code = str_replace('{$dbUser}', $this->config->dbUser, $code);
		$code = str_replace('{$dbPass}', $this->config->dbPass, $code);

		$code = str_replace('{$trainingResultTable}', $this->config->trainingResultTable, $code);

		$f = fopen($this->config->trainingFolder . '/equipment.php', 'w');
		fwrite($f, $code);
		fclose($f);
	}
} 
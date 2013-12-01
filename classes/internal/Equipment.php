<?php
namespace Alex\Internal;

/**
 * Class Equipment
 *
 * @package Alex\Internal
 */
class Equipment
{
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	private $trainingFolder;

	public function __construct($dbHost, $dbName, $dbUser, $dbPass, $trainingFolder)
	{
		$this->dbHost = $dbHost;
		$this->dbName = $dbName;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;

		$this->trainingFolder = $trainingFolder;
	}

	public function save()
	{
		$code = file_get_contents(__DIR__ . '/../../templates/equipment.php');

		$code = str_replace('{$dbHost}', $this->dbHost, $code);
		$code = str_replace('{$dbName}', $this->dbName, $code);
		$code = str_replace('{$dbUser}', $this->dbUser, $code);
		$code = str_replace('{$dbPass}', $this->dbPass, $code);

		$f = fopen($this->trainingFolder . '/equipment.php', 'w');
		fwrite($f, $code);
		fclose($f);
	}
} 
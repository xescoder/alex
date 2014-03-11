<?php

namespace Alex\Internal;

/**
 * Class Trainee
 *
 * @package Alex\Internal
 */
class Trainee
{
	/** @var  mixed */
	private $args;

	/** @var  string */
	private $body;

	/**
	 * @param mixed  $args
	 */
	public function __construct($args)
	{
		$this->args         = $args;
	}

	/**
	 * Create first
	 */
	public function create()
	{
		$template   = __DIR__ . '/../../templates/body.tpl';
		$this->body = file_get_contents($template);
	}

	/**
	 * @param string $parentBody
	 * @param string $mutate
	 */
	public function inherit($parentBody, $mutate)
	{
		$this->body = file_get_contents($parentBody);
		$this->body = $mutate($this->body);
	}

	/**
	 * @param string $trainingRoom
	 */
	public function train($trainingRoom)
	{
		// Make new folder in training room
		$folder = '';
		for ($i = 0; $i < 1000000; $i ++) {
			$name   = rand(0, 10000000000000);
			$folder = $trainingRoom . '/' . $name;
			if (! is_dir($folder)) {
				break;
			}
		}

		mkdir($folder);

		// Save body
		$f = fopen($folder . '/body.php', 'w');
		fwrite($f, $this->body);
		fclose($f);

		// Save trainee
		$trainee     = $folder . '/trainee.php';
		$traineeCode = file_get_contents(__DIR__ . '/../../templates/trainee.tpl');
		$traineeCode = str_replace('{$args}', serialize($this->args), $traineeCode);

		$f = fopen($trainee, 'w');
		fwrite($f, $traineeCode);
		fclose($f);

		// Execute trainee
		$cmd = 'php -f ' . $trainee . ' /dev/null &';
		pclose(popen($cmd, 'r'));
	}

	/**
	 * @param string $folder
	 *
	 * @return string
	 */
	public static function getBody($folder)
	{
		$path = $folder . '/body.php';
		return file_get_contents($path);
	}
}
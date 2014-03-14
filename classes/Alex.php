<?php

use \Alex\Internal\Trainer;

/**
 * Class Alex
 *
 * @property AlexConfig $config
 */
class Alex
{
	/** @var  Trainer */
	private $trainer;

	/**
	 * @param AlexConfig|null $config
	 */
	public function __construct($config = NULL)
	{
		$config = ($config instanceof AlexConfig) ? $config : new AlexConfig();
		$this->trainer = new Trainer($config);
	}

	/**
	 * @param string  $functionName
	 * @param mixed   $args
	 * @param Closure $estimate
	 */
	public function train($functionName, $args, $estimate)
	{
		$this->trainer->train($functionName, $args, $estimate);
	}

	/**
	 * @param string $functionName
	 * @param mixed  $args
	 */
	public function execute($functionName, $args)
	{
	}

	public function __get($property)
	{
		switch ($property) {
			case 'config':
				return $this->trainer->config;
		}
	}

	public function __set($property, $value)
	{
		switch ($property) {
			case 'config':
				$this->trainer->config = $value;
				break;
		}
	}
} 
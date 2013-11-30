<?php

/**
 * Class Alex
 *
 * @property AlexConfig $config
 */
class Alex
{
	/** @var  AlexConfig */
	private $config;

	/**
	 * @param string  $operationName
	 * @param array   $args
	 * @param Closure $estimate
	 */
	public function train($operationName, $args, $estimate)
	{
	}

	/**
	 * @param string $operationName
	 * @param array  $args
	 */
	public function execute($operationName, $args)
	{
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
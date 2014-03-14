<?php

/**
 * Class AlexMutation
 */
class AlexMutation
{
	private static $alexMutation;

	/**
	 * @return AlexMutation
	 */
	public static function init()
	{
		$class = get_called_class();

		if (!(self::$alexMutation instanceof $class)) {
			self::$alexMutation = new $class();
		}

		return self::$alexMutation;
	}

	private function __construct()
	{
	}

	/**
	 * @param string $source
	 *
	 * @return string
	 */
	public function mutate($source)
	{
		return $source;
	}
} 
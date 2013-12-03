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
		if (is_null(self::$alexMutation)) {
			self::$alexMutation = new self();
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
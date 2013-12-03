<?php
namespace Alex\Internal;

/**
 * Class Trainee
 *
 * @package Alex\Internal
 */
class Trainee
{
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
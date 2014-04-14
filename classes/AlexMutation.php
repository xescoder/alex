<?php

/**
 * Class AlexMutation
 */
class AlexMutation
{
	private static $alexMutation;

	private $operators;

	/**
	 * @return AlexMutation
	 */
	public static function init()
	{
		$class = get_called_class();

		if (! (self::$alexMutation instanceof $class)) {
			self::$alexMutation = new $class();
		}

		return self::$alexMutation;
	}

	private function __construct()
	{
		$this->operators = $this->readFileToArray(__DIR__ . '/../templates/mutation/operators.tpl');
	}

	/**
	 * @param string $path
	 * @return string[]
	 */
	private function readFileToArray($path)
	{
		$array = [];

		$file = fopen($path, 'r');
		while (! feof($file)) {
			$array[] = fgets($file, 1024);
		}
		fclose($file);

		return $array;
	}

	/**
	 * @return string
	 */
	private function getOperator()
	{
		$index = rand(0, count($this->operators) - 1);
		return $this->operators[$index];
	}

	private function createVar()
	{
		static $tokens = 'qwertyuioplkjhgfdsazxcvbnm';
		$len = strlen($tokens) - 1;

		$var = '$' . substr($tokens, rand(0, $len), 1);

		if (rand(0, 1000) < 300) {
			$var .= substr($tokens, rand(0, $len), 1);
		}

		if (rand(0, 1000) < 200) {
			$var .= substr($tokens, rand(0, $len), 1);
		}

		return $var;
	}

	private function getVars($source)
	{
		$vars = [];

		if ($source && preg_match_all('/\$[a-b][a-b0-9_]*/i', $source, $vars)) {
			if ($vars[0]) {
				$vars = array_unique($vars[0]);
			}
		}

		$len   = count($vars);
		$start = - ($len / 2);
		$end   = $len + ($len / 2) + 1;
		$count = ($len < 3) ? 3 : rand(0, 2);

		if (($len < 3) || (rand(0, 1000) < 300)) {
			for ($i = 0; $i < $count; $i ++) {
				$index = rand($start, $end);

				while (TRUE) {
					$var = $this->createVar();
					if (! in_array($var, $vars)) {
						$vars[$index] = $var;
						break;
					}
				}
			}
		}

		$vars = array_merge($vars, []);
		return $vars;
	}

	/**
	 * @param string $source
	 *
	 * @return string
	 */
	public function mutate($source)
	{
		$source = trim($source);

		$vars  = $this->getVars($source);
		$lines = explode(PHP_EOL, $source);
		$len   = count($lines);

		$count = rand(0, ($len / 10) + 3);
		$start = - ($len / 2);
		$end   = $len + ($len / 2) + 1;

		for ($i = 0; $i < $count; $i ++) {
			$operator = $this->getOperator();
			$index    = rand($start, $end);

			$lines[$index] = preg_replace_callback('/\{\$p[0-9]\}/', function () use ($vars) {
				$index = rand(0, count($vars) - 1);
				return $vars[$index];
			}, $operator);
		}

		foreach ($lines as $index => $line) {
			$line = trim($line);

			if ($line) {
				$lines[$index] = $line;
			}
			else {
				unset($lines[$index]);
			}
		}

		ksort($lines);
		$source = implode(PHP_EOL, $lines);

		return $source;
	}
} 
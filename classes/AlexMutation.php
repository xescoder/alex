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
		static $tokens = 'abcdefghijklmn';
		$len = strlen($tokens) - 1;

		$var = '$' . substr($tokens, rand(0, $len), 1);

		return $var;
	}

	private function createValue()
	{
		return rand(-1000, 1000);
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
		$count = ($len < 1) ? 1 : rand(0, 2);

		if (($len < 1) || (rand(0, 1000) < 50)) {
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
		if (rand(0, 1000) > 700) {
			return $source;
		}

		$source = trim($source);
		$source = preg_replace('/\<\?php\s/', '', $source);

		$vars  = $this->getVars($source);
		$lines = explode(PHP_EOL, $source);
		$len   = count($lines);

		if (count($vars) == 0) {
			$source = '<?php' . PHP_EOL . $source;
			return $source;
		}

		$count = rand(0, ($len / 10) + 3);
		$start = - ($len / 2);
		$end   = $len + ($len / 2) + 1;

		for ($i = 0; $i < $count; $i ++) {
			$operator = $this->getOperator();
			$index    = rand($start, $end);

			if (rand(0, 1000) < 100) {
				if (isset($lines[$index])) {
					unset($lines[$index]);
				}
			}
			else {
				$lines[$index] = preg_replace_callback('/\{\$p[0-9]\}/', function () use ($vars) {
					if (rand(0, 1000) < 50) {
						return $this->createValue();
					}
					else {
						$index = rand(0, count($vars) - 1);
						return $vars[$index];
					}
				}, $operator);
			}
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
		$source = '<?php' . PHP_EOL . $source;

		return $source;
	}
} 
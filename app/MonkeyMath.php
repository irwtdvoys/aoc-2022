<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use Exception;

	class MonkeyMath extends Helper
	{
		/** @var array */
		public array $monkeys;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);
			$rows = explode(PHP_EOL, $raw);

			foreach ($rows as $row)
			{
				[$name, $value] = explode(": ", $row);

				$parts = explode(" ", $value);

				$this->monkeys[$name] = count($parts) === 1 ? (int)$value : $parts;
			}
		}

		private function getValue(string $name): int
		{
			if (is_int($this->monkeys[$name]))
			{
				return $this->monkeys[$name];
			}

			$first = $this->getValue($this->monkeys[$name][0]);
			$second = $this->getValue($this->monkeys[$name][2]);

			switch ($this->monkeys[$name][1])
			{
				case "+":
					$result = $first + $second;
					break;
				case "-":
					$result = $first - $second;
					break;
				case "*":
					$result = $first * $second;
					break;
				case "/":
					$result = $first / $second;
					break;
			}

			$this->monkeys[$name] = $result;

			return $result;
		}

		private function output(): void
		{
			foreach ($this->monkeys as $name => $monkey)
			{
				if (!is_array($monkey))
				{
					$monkey = [$monkey];
				}

				echo($name . ": " . implode(" ", $monkey) . PHP_EOL);
			}

			echo(PHP_EOL);
		}

		private function calcaulate(string $name, int $target): int
		{
			$monkey = $this->monkeys[$name];

			if (is_int($this->monkeys[$monkey[0]]))
			{
				$newName = $monkey[2];

				switch ($monkey[1])
				{
					case "+":
						$newTarget = $target - $this->monkeys[$monkey[0]];
						break;
					case "-":
						$newTarget = $this->monkeys[$monkey[0]] - $target;
						break;
					case "*":
						$newTarget = $target / $this->monkeys[$monkey[0]];
						break;
					case "/":
						$newTarget = $this->monkeys[$monkey[0]] / $target;
						break;
				}
			}
			elseif (is_int($this->monkeys[$monkey[2]]))
			{
				$newName = $monkey[0];

				switch ($monkey[1])
				{
					case "+":
						$newTarget = $target - $this->monkeys[$monkey[2]];
						break;
					case "-":
						$newTarget = $target + $this->monkeys[$monkey[2]];
						break;
					case "*":
						$newTarget = $target / $this->monkeys[$monkey[2]];
						break;
					case "/":
						$newTarget = $target * $this->monkeys[$monkey[2]];
						break;
				}
			}

			return ($newName === "humn") ? $newTarget : $this->calcaulate($newName, $newTarget);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$cache = $this->monkeys;
			$result->part1 = $this->getValue("root");

			$this->monkeys = $cache;
			unset($this->monkeys['humn']);

			$errors = [];

			foreach ($this->monkeys as $name => $monkey)
			{
				try
				{
					$this->getValue($name);
				}
				catch (Exception $exception)
				{
					$errors[] = $name;
				}
			}

			if (in_array($this->monkeys['root'][0], $errors))
			{
				$safe = 2;
				$calculate = 0;
			}
			else
			{
				$safe = 0;
				$calculate = 2;
			}

			$target = $this->getValue($this->monkeys['root'][$safe]);
			$this->monkeys['humn'] = "x";

			$result->part2 = $this->calcaulate($this->monkeys['root'][$calculate], $target);

			return $result;
		}
	}
?>

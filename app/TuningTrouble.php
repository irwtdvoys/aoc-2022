<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class TuningTrouble extends Helper
	{
		public string $signal = "";

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$this->signal = parent::load($override);
		}

		private function check(int $size): int
		{
			$result = -1;

			for ($index = 0; $index < strlen($this->signal) - ($size - 1); $index++)
			{
				$current = substr($this->signal, $index, $size);

				if (strlen(count_chars($current, 3)) === $size)
				{
					$result = $index + $size;
					break;
				}
			}

			return $result;
		}

		public function run(): Result
		{
			return new Result(
				$this->check(4),
				$this->check(14)
			);
		}
	}
?>

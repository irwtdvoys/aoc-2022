<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class FullOfHotAir extends Helper
	{
		/** @var string[] */
		public array $numbers;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->numbers = explode(PHP_EOL, $raw);
		}

		private function snafuToDecimal(string $number): int
		{
			$parts = str_split($number);
			$count = count($parts);
			$result = 0;

			for ($place = 0; $place < $count; $place++)
			{
				$part = $parts[$count - 1 - $place];

				$value = match ($part)
				{
					"-" => -1,
					"=" => -2,
					default => (int)$part
				};

				$result += $value * (pow(5, $place));

			}

			return $result;
		}

		private function decimalToSnafu(int $number): string
		{
			$result = "";
			$quotient = $number;

			while ($quotient > 0)
			{
				$remainder = (($quotient + 2) % 5) - 2;
				$quotient = floor(($quotient + 2) / 5);
				$result .= match ($remainder)
				{
					-2 => "=",
					-1 => "-",
					default => (string)$remainder
				};
			}

			return strrev($result);
		}

		public function run(): Result
		{
			$total = 0;

			foreach ($this->numbers as $snafu)
			{
				$decimal = $this->snafuToDecimal($snafu);

				$total += $decimal;

				if ($this->verbose)
				{
					echo($snafu . "\t" . $decimal . PHP_EOL);
				}
			}

			return new Result($this->decimalToSnafu($total), "Merry Christmas");
		}
	}
?>

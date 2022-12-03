<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\RucksackReorganization\Rucksack;

	class RucksackReorganization extends Helper
	{
		/** @var Rucksack[] */
		public array $rucksacks;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->rucksacks = array_map(
				function ($element)
				{
					return new Rucksack($element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		private function priority(string $value): int
		{
			$result = ord($value);
			$result -= ($result < 97) ? 38 : 96;

			return $result;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$group = [];
			$count = 0;

			foreach ($this->rucksacks as $rucksack)
			{
				$inBoth = $rucksack->both();

				foreach ($inBoth as $next)
				{
					$result->part1 += $this->priority($next);
				}

				$group[] = $rucksack->all();
				$count++;

				if ($count === 3)
				{
					$badge = array_values(array_unique(array_intersect(...$group)))[0];
					$result->part2 += $this->priority($badge);

					$group = [];
					$count = 0;
				}
			}

			return $result;
		}
	}
?>

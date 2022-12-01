<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\CalorieCounting\Elf;

	class CalorieCounting extends Helper
	{
		/** @var int[][] */
		public array $elves;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->elves = array_map(
				function ($data)
				{
					return new Elf($data);
				},
				explode(PHP_EOL . PHP_EOL, $raw)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);
			$counts = [];

			foreach ($this->elves as $elf)
			{
				$counts[] = $elf->total();
			}

			$result->part1 = max($counts);
			rsort($counts);
			$result->part2 = array_sum(array_slice($counts, 0, 3));

			return $result;
		}
	}
?>

<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\CampCleanup\Pair;

	class CampCleanup extends Helper
	{
		/** @var Pair[] */
		public array $pairs;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->pairs = array_map(
				function ($data)
				{
					return new Pair($data);
				},
				explode(PHP_EOL, $raw)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->pairs as $pair)
			{
				if ($pair->contained())
				{
					$result->part1++;
				}

				if ($pair->overlaps())
				{
					$result->part2++;
				}
			}

			return $result;
		}
	}
?>
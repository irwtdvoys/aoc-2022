<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class DistressSignal extends Helper
	{
		public array $pairs = [];

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$pairs = explode(PHP_EOL . PHP_EOL, $raw);

			foreach ($pairs as $index => $pair)
			{
				$this->pairs[$index + 1] = array_map("json_decode", explode(PHP_EOL, $pair));
			}
		}

		public static function compare($left = null, $right = null): int
		{
			if (is_int($left) && is_int($right))
			{
				return $left <=> $right;
			}

			if (!is_int($left) && is_int($right))
			{
				$right = [$right];
			}

			if (is_int($left) && !is_int($right))
			{
				$left = [$left];
			}

			while (count($left) && count($right))
			{
				$result = self::compare(array_shift($left), array_shift($right));

				if ($result)
				{
					return $result;
				}
			}

			return count($left) - count($right);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$dividers = [
				[[2]],
				[[6]]
			];

			$all = $dividers;

			foreach ($this->pairs as $index => $pair)
			{
				$all = array_merge($all, $pair);

				$comparison = $this->compare($pair[0], $pair[1]);

				if ($comparison < 0)
				{
					$result->part1 += $index;
				}
			}

			usort($all, [self::class, "compare"]);

			foreach ($all as &$item)
			{
				$item = json_encode($item);
			}

			$indices = [];

			foreach ($dividers as $divider)
			{
				$indices[] = array_search(json_encode($divider), $all) + 1;
			}

			$result->part2 = array_product($indices);

			return $result;
		}
	}

	// 739 low
?>

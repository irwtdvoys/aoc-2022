<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\MonkeyInTheMiddle\Monkey;
	use Bolt\Maths;

	class MonkeyInTheMiddle extends Helper
	{
		/** @var Monkey[] */
		public array $monkeys;

		public int $tmp;

		public function __construct(int $day, bool|int $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->monkeys = array_map(
				function ($element)
				{
					return new Monkey($element);
				},
				explode(PHP_EOL . PHP_EOL, $raw)
			);

			$divisors = [];

			foreach ($this->monkeys as $monkey)
			{
				$divisors[] = $monkey->test;
			}

			$this->tmp = Maths::lcm(...$divisors);
		}

		private function levels(): void
		{
			foreach ($this->monkeys as $index => $monkey)
			{
				echo("Monkey $index: " . implode(", ", $monkey->items) . PHP_EOL);
			}
		}

		private function activity(): void
		{
			foreach ($this->monkeys as $index => $monkey)
			{
				echo("Monkey $index inspected items " . $monkey->count . " times." . PHP_EOL);
			}
		}

		private function monkeyBusiness(): int
		{
			$counts = [];

			foreach ($this->monkeys as $monkey)
			{
				$counts[] = $monkey->count;
			}

			rsort($counts);

			return $counts[0] * $counts[1];
		}

		private function round(bool $reduceWorry = true): void
		{
			foreach ($this->monkeys as $monkey)
			{
				while (($item = $monkey->inspect($reduceWorry)) !== null)
				{
					$target = $monkey->target($item);

					$this->monkeys[$target]->catches($item % $this->tmp);
				}
			}

			if ($this->verbose)
			{
				$this->levels();
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$initialState = array_map(
				function ($object)
				{
					return clone $object;
				},
				$this->monkeys
			);

			for ($round = 1; $round <= 20; $round++)
			{
				$this->round();
			}

			if ($this->verbose)
			{
				$this->activity();
			}

			$result->part1 = $this->monkeyBusiness();

			$this->monkeys = $initialState;

			for ($round = 1; $round <= 10000; $round++)
			{
				$this->round(false);
			}

			if ($this->verbose)
			{
				$this->activity();
			}

			$result->part2 = $this->monkeyBusiness();

			return $result;
		}
	}
?>

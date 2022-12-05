<?php

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\SupplyStacks\Step;
	use Bolt\Files;

	class SupplyStacks extends Helper
	{
		/** @var Step[] */
		public array $procedure = [];
		/** @var string[][] */
		public array $stacks = [];

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = (new Files())->load($this->filename($override));

			[$stacks, $procedure] = explode(str_repeat(PHP_EOL, 2), $raw);

			$stacks = explode(PHP_EOL, $stacks);
			$labels = trim(array_pop($stacks));
			$stacks = array_reverse($stacks);
			$columnCount = (int)$labels[strlen($labels) - 1];
			$this->stacks = array_fill(1, $columnCount, []);

			foreach ($stacks as $stack)
			{
				$data = str_split($stack);

				for ($index = 1; $index < count($data); $index += 4)
				{
					if ($data[$index] !== " ")
					{
						$id = floor($index / 4) + 1;

						if (!$this->stacks[$id])
						{
							$this->stacks[$id] = [];
						}

						$this->stacks[$id][] = $data[$index];
					}
				}
			}

			$this->procedure = array_map(
				function ($element)
				{
					return new Step($element);
				},
				explode(PHP_EOL, trim($procedure))
			);
		}

		public function run(): Result
		{
			$result = new Result("", "");

			$cache = $this->stacks;

			foreach ($this->procedure as $step)
			{
				for ($loop = 1; $loop <= $step->move; $loop++)
				{
					$element = array_pop($this->stacks[$step->from]);
					$this->stacks[$step->to][] = $element;
				}
			}

			foreach ($this->stacks as $stack)
			{
				$result->part1 .= $stack[count($stack) - 1];
			}

			$this->stacks = $cache;

			foreach ($this->procedure as $step)
			{
				$section = array_slice($this->stacks[$step->from], -$step->move);

				$this->stacks[$step->from] = array_slice($this->stacks[$step->from], 0, (count($this->stacks[$step->from]) - $step->move));
				array_push($this->stacks[$step->to], ...$section);
			}

			foreach ($this->stacks as $stack)
			{
				$result->part2 .= $stack[count($stack) - 1];
			}

			return $result;
		}
	}
?>

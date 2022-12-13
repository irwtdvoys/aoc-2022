<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Styling;
	use App\HillClimbingAlgorithm\Cell;

	class HillClimbingAlgorithm extends Helper
	{
		/** @var Cell[][] */
		public array $heightMap = [];

		public Cell $source;
		public Cell $destination;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$lines = explode(PHP_EOL, $raw);

			foreach ($lines as $y => $line)
			{
				$cells = str_split($line);

				foreach ($cells as $x => $cell)
				{
					switch ($cell)
					{
						case "S":
							$this->heightMap[$x][$y] = new Cell($x, $y, ord("a"));
							$this->source = $this->heightMap[$x][$y];
							break;
						case "E":
							$this->heightMap[$x][$y] = new Cell($x, $y, ord("z"));
							$this->destination = $this->heightMap[$x][$y];
							break;
						default:
							$this->heightMap[$x][$y] = new Cell($x, $y, ord($cell));
							break;
					}
				}
			}
		}

		public function draw(): void
		{
			for ($y = 0; $y < count($this->heightMap[0]); $y++)
			{
				for ($x = 0; $x < count($this->heightMap); $x++)
				{
					if ($x === $this->source->position->x && $y === $this->source->position->y)
					{
						echo(Styling::format([Styling::BG_GREEN], "S"));
					}
					elseif ($x === $this->destination->position->x && $y === $this->destination->position->y)
					{
						echo(Styling::format([Styling::BG_RED], "E"));
					}
					else
					{
						echo(chr($this->heightMap[$x][$y]->value));
					}
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		private function reset(): void
		{
			foreach ($this->heightMap as $column)
			{
				foreach ($column as $cell)
				{
					$cell->shortest = PHP_INT_MAX;
				}
			}
		}

		private function search(Cell $source, bool $reverse = false)
		{
			$source->shortest = 0;
			$queue = [$source];

			$width = count($this->heightMap);
			$height = count($this->heightMap[0]);

			while (count($queue) > 0)
			{
				$node = array_pop($queue);
				$neighbours = [];
				$position = $node->position;

				// N
				if ($position->y > 0)
				{
					$neighbours[] = $this->heightMap[$position->x][$position->y - 1];
				}
				// E
				if ($position->x > 0)
				{
					$neighbours[] = $this->heightMap[$position->x - 1][$position->y];
				}
				// S
				if ($position->y < $height - 1)
				{
					$neighbours[] = $this->heightMap[$position->x][$position->y + 1];
				}
				// W
				if ($position->x < $width - 1)
				{
					$neighbours[] = $this->heightMap[$position->x + 1][$position->y];
				}

				foreach ($neighbours as $neighbour)
				{
					if (
						!$reverse && ($neighbour->value <= $node->value + 1 && $neighbour->shortest > ($node->shortest + 1)) ||
						$reverse && (($neighbour->value >= $node->value - 1 && $neighbour->shortest > ($node->shortest + 1)))
					)
					{
						$neighbour->shortest = $node->shortest + 1;
						$queue[] = $neighbour;
					}
				}
			}
		}

		private function findShortestStart(): int
		{
			$min = PHP_INT_MAX;

			for ($y = 0; $y < count($this->heightMap[0]); $y++)
			{
				for ($x = 0; $x < count($this->heightMap); $x++)
				{
					$node = $this->heightMap[$x][$y];

					if ($node->value === ord("a"))
					{
						$min = min($min, $node->shortest);
					}
				}
			}

			return $min;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			if ($this->verbose)
			{
				$this->draw();
			}

			$this->search($this->source);
			$result->part1 = $this->destination->shortest;

			$this->reset();
			$this->search($this->destination, true);
			$result->part2 = $this->findShortestStart();

			return $result;
		}
	}
?>

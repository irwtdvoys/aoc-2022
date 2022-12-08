<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Styling;

	class TreetopTreeHouse extends Helper
	{
		/** @var int[][] */
		private array $grid = [];
		private int $size = 0;
		/** @var string[] */
		private array $matches = [];

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);
			$rows = explode(PHP_EOL, $raw);
			$this->size = count($rows);

			for ($y = 0; $y < $this->size; $y++)
			{
				$row = $rows[$y];

				for ($x = 0; $x < $this->size; $x++)
				{
					$this->grid[$x][$y] = (int)$row[$x];
				}
			}
		}

		private function draw(): void
		{
			for ($y = 0; $y < $this->size; $y++)
			{
				for ($x = 0; $x < $this->size; $x++)
				{
					$visible = $this->isVisible($x, $y);

					$format = [
						$visible ? Styling::BG_GREEN : Styling::BG_BLUE,
						in_array($x . "," . $y, $this->matches) ? Styling::RED : Styling::WHITE
					];
					echo(Styling::format($format, $this->grid[$x][$y]));
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		private function isVisible(int $x, int $y): bool
		{
			$max = $this->size - 1;

			if ($x === 0 || $x === $max || $y === 0 || $y === $max)
			{
				return true;
			}

			$up = $right = $down = $left = true;

			// up
			for ($index = $y - 1; $index >= 0; $index--)
			{
				if ($this->grid[$x][$y] <= $this->grid[$x][$index])
				{
					$up = false;
				}
			}

			// right
			for ($index = $x + 1; $index < $this->size; $index++)
			{
				if ($this->grid[$x][$y] <= $this->grid[$index][$y])
				{
					$right = false;
				}
			}

			// down
			for ($index = $y + 1; $index < $this->size; $index++)
			{
				if ($this->grid[$x][$y] <= $this->grid[$x][$index])
				{
					$down = false;
				}
			}

			// left
			for ($index = $x - 1; $index >= 0; $index--)
			{
				if ($this->grid[$x][$y] <= $this->grid[$index][$y])
				{
					$left = false;
				}
			}

			return $up || $right || $down || $left;
		}

		private function score(int $x, int $y): int
		{
			$up = $right = $down = $left = 0;

			// up
			for ($index = $y - 1; $index >= 0; $index--)
			{
				$up++;

				if ($this->grid[$x][$y] <= $this->grid[$x][$index])
				{
					break;
				}
			}

			// right
			for ($index = $x + 1; $index < $this->size; $index++)
			{
				$right++;

				if ($this->grid[$x][$y] <= $this->grid[$index][$y])
				{
					break;
				}
			}

			// down
			for ($index = $y + 1; $index < $this->size; $index++)
			{
				$down++;

				if ($this->grid[$x][$y] <= $this->grid[$x][$index])
				{
					break;
				}
			}

			// left
			for ($index = $x - 1; $index >= 0; $index--)
			{
				$left++;

				if ($this->grid[$x][$y] <= $this->grid[$index][$y])
				{
					break;
				}
			}

			return array_product([$up, $right, $down, $left]);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$scores = [];

			for ($y = 0; $y < $this->size; $y++)
			{
				for ($x = 0; $x < $this->size; $x++)
				{
					if ($this->isVisible($x, $y))
					{
						$result->part1++;
					}

					$score = $this->score($x, $y);
					$scores[$score][] = $x . "," . $y;

					if ($score >= $result->part2)
					{
						$result->part2 = $score;
					}
				}
			}

			$this->matches = $scores[$result->part2];

			if ($this->verbose)
			{
				$this->draw();
			}

			return $result;
		}
	}
?>

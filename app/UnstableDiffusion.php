<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Dimensions2d;
	use AoC\Utils\Position2d;
	use App\UnstableDiffusion\Directions;

	class UnstableDiffusion extends Helper
	{
		/** @var Position2d[] */
		public array $elves;

		public array $current = [];
		public array $proposed = [];

		public array $order = [Directions::North, Directions::South, Directions::West, Directions::East];

		public Dimensions2d $dimensions;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);
			$rows = explode(PHP_EOL, $raw);

			for ($y = 0; $y < count($rows); $y++)
			{
				$cells = str_split($rows[$y]);

				for ($x = 0; $x < count($cells); $x++)
				{
					$cell = $cells[$x];

					if ($cell === "#")
					{
						$this->elves[] = new Position2d($x, $y);
					}
				}
			}

			$this->mapElves();
		}

		private function mapElves()
		{
			$this->current = [];
			$this->dimensions = new Dimensions2d();

			foreach ($this->elves as $elf)
			{
				$this->current[(string)$elf] = $elf;

				$this->dimensions->x->add($elf->x);
				$this->dimensions->y->add($elf->y);
			}
		}

		private function rotateDirections()
		{
			$this->order[] = array_shift($this->order);
		}

		private function availableMoves($elf): array
		{
			$result = [false, false, false, false];

			if (
				!isset($this->current[$elf->x - 1 . "," . $elf->y - 1]) &&
				!isset($this->current[$elf->x . "," . $elf->y - 1]) &&
				!isset($this->current[$elf->x + 1 . "," . $elf->y - 1])
			)
			{
				$result[0] = true;
			}

			if (
				!isset($this->current[$elf->x - 1 . "," . $elf->y + 1]) &&
				!isset($this->current[$elf->x . "," . $elf->y + 1]) &&
				!isset($this->current[$elf->x + 1 . "," . $elf->y + 1])
			)
			{
				$result[1] = true;
			}

			if (
				!isset($this->current[$elf->x - 1 . "," . $elf->y - 1]) &&
				!isset($this->current[$elf->x - 1 . "," . $elf->y]) &&
				!isset($this->current[$elf->x - 1 . "," . $elf->y + 1])
			)
			{
				$result[2] = true;
			}

			if (
				!isset($this->current[$elf->x + 1 . "," . $elf->y - 1]) &&
				!isset($this->current[$elf->x + 1 . "," . $elf->y]) &&
				!isset($this->current[$elf->x + 1 . "," . $elf->y + 1])
			)
			{
				$result[3] = true;
			}

			if ($result === [true, true, true, true])
			{
				$result = [false, false, false, false];
			}


			return $result;
		}

		private function move(): int
		{
			$count = 0;

			foreach ($this->proposed as $key => $value)
			{
				if (count($value) === 1)
				{
					[$x, $y] = explode(",", $key);
					$value[0]->x = $x;
					$value[0]->y = $y;

					$count++;
				}
			}

			$this->proposed = [];

			return $count;
		}

		private function draw(?int $round = null): void
		{
			if ($round)
			{
				echo("== END OF ROUND " . $round . " == " . PHP_EOL);
			}

			for ($y = $this->dimensions->y->min; $y <= $this->dimensions->y->max; $y++)
			{
				for ($x = $this->dimensions->x->min; $x <= $this->dimensions->x->max; $x++)
				{
					echo(isset($this->current[$x . "," . $y]) ? "#" : ".");
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$count = PHP_INT_MAX;
			$round = 0;

			if ($this->verbose)
			{
				$this->draw($round);
			}

			while ($count > 0)
			{
				foreach ($this->elves as $elf)
				{
					$moves = $this->availableMoves($elf);

					foreach ($this->order as $direction)
					{
						if ($moves[$direction->value] === true)
						{
							switch ($direction)
							{
								case Directions::North:
									$x = $elf->x;
									$y = $elf->y - 1;
									break;
								case Directions::South:
									$x = $elf->x;
									$y = $elf->y + 1;
									break;
								case Directions::West:
									$x = $elf->x - 1;
									$y = $elf->y;
									break;
								case Directions::East:
									$x = $elf->x + 1;
									$y = $elf->y;
									break;
							}

							$this->proposed[$x . "," . $y][] = $elf;
							break;
						}
					}
				}

				$count = $this->move();
				$this->rotateDirections();
				$this->mapElves();
				$round++;

				if ($this->verbose)
				{
					$this->draw($round);
				}

				if ($round === 10)
				{
					$result->part1 = $this->dimensions->area() - count($this->elves);
				}
			}

			$result->part2 = $round;

			return $result;
		}
	}
?>

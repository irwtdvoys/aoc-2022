<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use AoC\Utils\Range;
	use App\RegolithReservoir\Tile;
	use Exception;

	class RegolithReservoir extends Helper
	{
		public array $cave = [[]];
		public Position2d $entry;
		public Range $rangeX;
		public Range $rangeY;
		public bool $void = true;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->entry = new Position2d(500, 0);
			$this->cave = [500 => [0 => Tile::Entry]];
			$this->rangeX = new Range(500, 500);
			$this->rangeY = new Range(0, 0);

			$paths = explode(PHP_EOL, $raw);

			foreach ($paths as $path)
			{
				$points = explode(" -> ", $path);

				for ($index = 0; $index < count($points) - 1; $index++)
				{
					$from = new Position2d(...explode(",", $points[$index]));
					$to = new Position2d(...explode(",", $points[$index + 1]));

					$this->line($from, $to);
				}
			}
		}

		private function line(Position2d $from, Position2d $to): void
		{
			for ($x = min($from->x, $to->x); $x <= max($from->x, $to->x); $x++)
			{
				for ($y = min($from->y, $to->y); $y <= max($from->y, $to->y); $y++)
				{
					$this->add($x, $y);
				}
			}
		}

		private function add(int $x, int $y, Tile $value = Tile::Wall): void
		{
			$this->cave[$x][$y] = $value;
			$this->rangeX->add($x);
			$this->rangeY->add($y);
		}

		private function clear(): void
		{
			for ($y = $this->rangeY->min; $y <= $this->rangeY->max; $y++)
			{
				for ($x = $this->rangeX->min; $x <= $this->rangeX->max; $x++)
				{
					if (isset($this->cave[$x][$y]) && $this->cave[$x][$y] === Tile::Sand)
					{
						$this->cave[$x][$y] = Tile::Empty;
					}
				}
			}
		}

		private function draw(): void
		{
			for ($y = $this->rangeY->min; $y <= $this->rangeY->max; $y++)
			{
				echo(str_pad($y, strlen($this->rangeY->max), " ", STR_PAD_LEFT) . " ");

				for ($x = $this->rangeX->min; $x <= $this->rangeX->max; $x++)
				{
					echo(($this->cave[$x][$y] ?? Tile::Empty)->value);
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		private function fillCell(int $x, int $y)
		{
			if ($y > $this->rangeY->max)
			{
				throw new Exception("Overflow");
			}

			if (!isset($this->cave[$x]))
			{
				$this->cave[$x] = [];
			}

			if (!isset($this->cave[$x][$y]))
			{
				$this->add($x, $y, (!$this->void && $y === $this->rangeY->max) ? Tile::Wall : Tile::Empty);
			}
		}

		private function drop(): void
		{
			$sand = clone $this->entry;

			while (true)
			{
				$this->fillCell($sand->x, $sand->y + 1);
				$this->fillCell($sand->x - 1, $sand->y + 1);
				$this->fillCell($sand->x + 1, $sand->y + 1);

				if ($this->cave[$sand->x][$sand->y + 1] === Tile::Empty)
				{
					$sand->y++;
				}
				elseif ($this->cave[$sand->x - 1][$sand->y + 1] === Tile::Empty)
				{
					$sand->x--;
					$sand->y++;
				}
				elseif ($this->cave[$sand->x + 1][$sand->y + 1] === Tile::Empty)
				{
					$sand->x++;
					$sand->y++;
				}
				else
				{
					if ($this->cave[$sand->x][$sand->y] === Tile::Entry)
					{
						throw new Exception("Filled");
					}

					$this->cave[$sand->x][$sand->y] = Tile::Sand;
					break;
				}
			}
		}

		private function pour(): int
		{
			$index = 1;

			while (true)
			{
				try
				{
					$this->drop();

					if ($this->verbose)
					{
						$this->draw();
					}
				}
				catch (Exception $exception)
				{
					return $index - 1;
				}

				$index++;
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);


			$result->part1 = $this->pour();

			$this->clear();
			$this->line(
				new Position2d($this->rangeX->min, $this->rangeY->max + 2),
				new Position2d($this->rangeX->max, $this->rangeY->max + 2)
			);

			$this->void = false;
			$result->part2 = $this->pour() + 1;

			$this->draw();

			return $result;
		}
	}
?>

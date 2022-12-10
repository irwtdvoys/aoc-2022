<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use App\RopeBridge\Direction;
	use App\RopeBridge\Motion;
	use App\RopeBridge\Ranges;

	class RopeBridge extends Helper
	{
		/** @var Motion[] */
		public array $motions;
		/** @var Position2d[] */
		public array $rope = [];
		public array $visited = [];
		public Ranges $ranges;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->motions = array_map(
				function ($element)
				{
					[$direction, $distance] = explode(" ", $element);

					return new Motion($direction, $distance);
				},
				explode(PHP_EOL, $raw)
			);
		}

		private function move(Position2d $position, Direction $direction): void
		{
			switch ($direction)
			{
				case Direction::Up:
					$position->y--;
					break;
				case Direction::Right:
					$position->x++;
					break;
				case Direction::Down:
					$position->y++;
					break;
				case Direction::Left:
					$position->x--;
					break;
			}
		}

		private function step($direction): void
		{
			$head = $this->rope[0];

			switch ($direction)
			{
				case "U":
					$this->move($head, Direction::Up);
					break;
				case "R":
					$this->move($head, Direction::Right);
					break;
				case "D":
					$this->move($head, Direction::Down);
					break;
				case "L":
					$this->move($head, Direction::Left);
					break;
			}

			$this->ranges->x->add($head->x);
			$this->ranges->y->add($head->y);

			for ($index = 1; $index < count($this->rope); $index++)
			{
				$knot = $this->rope[$index];
				$previous = $this->rope[$index - 1];

				// move knot
				$diffX = $previous->x - $knot->x;
				$diffY = $previous->y - $knot->y;

				$isDiagonal = $diffX !== 0 && $diffY !== 0;

				$directions = [];

				if ($isDiagonal === true && (abs($diffX) + abs($diffY)) > 2)
				{
					if ($diffX >= 1)
					{
						$directions[] = Direction::Right;
					}
					elseif ($diffX <= -1)
					{
						$directions[] = Direction::Left;
					}

					if ($diffY >= 1)
					{
						$directions[] = Direction::Down;
					}
					elseif ($diffY <= -1)
					{
						$directions[] = Direction::Up;
					}
				}
				elseif ($isDiagonal === false)
				{
					if ($diffX > 1)
					{
						$directions[] = Direction::Right;
					}
					elseif ($diffX < -1)
					{
						$directions[] = Direction::Left;
					}

					if ($diffY > 1)
					{
						$directions[] = Direction::Down;
					}
					elseif ($diffY < -1)
					{
						$directions[] = Direction::Up;
					}
				}

				foreach ($directions as $next)
				{
					$this->move($knot, $next);
				}
			}

			$tail = $this->rope[count($this->rope) - 1];

			if (!isset($this->visited[(string)$tail]))
			{
				$this->visited[(string)$tail] = 1;
			}
			else
			{
				$this->visited[(string)$tail]++;
			}
		}

		private function generate(int $knots): array
		{
			$rope = [];

			for ($index = 0; $index < $knots; $index++)
			{
				$rope[] = new Position2d();
			}

			return $rope;
		}

		private function draw(): void
		{
			for ($y = $this->ranges->y->min; $y <= $this->ranges->y->max; $y++)
			{
				for ($x = $this->ranges->x->min; $x <= $this->ranges->x->max; $x++)
				{
					$value = ($x === 0 && $y === 0) ? "s" : ".";

					for ($index = count($this->rope) - 1; $index >= 0; $index--)
					{
						if ($this->rope[$index]->x === $x && $this->rope[$index]->y === $y)
						{
							$value = match ($index)
							{
								0 => "H",
								count($this->rope) - 1 => "T",
								default => $index,
							};
						}
					}

					echo($value);
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		private function initialise(int $knots)
		{
			$this->rope = $this->generate($knots);
			$this->ranges = new Ranges();
			$this->visited = [];
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$this->initialise(2);

			foreach ($this->motions as $motion)
			{
				for ($index = 0; $index < $motion->distance; $index++)
				{
					$this->step($motion->direction);

					if ($this->verbose)
					{
						$this->draw();
					}
				}
			}

			$result->part1 = count($this->visited);

			$this->initialise(10);

			foreach ($this->motions as $motion)
			{
				for ($index = 0; $index < $motion->distance; $index++)
				{
					$this->step($motion->direction);
				}

				if ($this->verbose)
				{
					$this->draw();
				}
			}

			$result->part2 = count($this->visited);

			return $result;
		}
	}
?>

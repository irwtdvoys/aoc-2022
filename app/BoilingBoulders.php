<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Dimensions3d;
	use AoC\Utils\Position3d;

	class BoilingBoulders extends Helper
	{
		/** @var bool[][][] */
		public array $region;
		public Dimensions3d $dimensions;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$coords = explode(PHP_EOL, $raw);

			$this->dimensions = new Dimensions3d();

			foreach ($coords as $coord)
			{
				[$x, $y, $z] = explode(",", $coord);

				$this->region[$x][$y][$z] = true;
				$this->dimensions->x->add($x);
				$this->dimensions->y->add($y);
				$this->dimensions->z->add($z);
			}
		}

		private function check(int $x, int $y, int $z): bool
		{
			return !empty($this->region[$x][$y][$z]);
		}

		private function adjacent(int $x, int $y, int $z): int
		{
			return (
				$this->check($x - 1, $y, $z) +
				$this->check($x + 1, $y, $z) +
				$this->check($x, $y - 1, $z) +
				$this->check($x, $y + 1, $z) +
				$this->check($x, $y, $z - 1) +
				$this->check($x, $y, $z + 1)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			for ($x = $this->dimensions->x->min; $x <= $this->dimensions->x->max; $x++)
			{
				for ($y = $this->dimensions->y->min; $y <= $this->dimensions->y->max; $y++)
				{
					for ($z = $this->dimensions->z->min; $z <= $this->dimensions->z->max; $z++)
					{
						if ($this->check($x, $y, $z))
						{
							$result->part1 += 6 - $this->adjacent($x, $y, $z);
						}
					}
				}
			}

			$queue = [
				(string)new Position3d(
					$this->dimensions->x->min - 1,
					$this->dimensions->y->min - 1,
					$this->dimensions->z->min - 1
				)
			];

			$visited = [];

			while (count($queue) > 0)
			{
				$point = new Position3d(...explode(",", array_pop($queue)));
				$visited[] = (string)$point;

				$newXs = [
					$point->x + 1,
					$point->x - 1
				];

				foreach ($newXs as $newX)
				{
					if ($newX > $this->dimensions->x->min - 1 && $newX <= $this->dimensions->x->max + 1)
					{
						$check = $this->check($newX, $point->y, $point->z);

						if ($check === true)
						{
							$result->part2++;
						}
						else
						{
							$newPoint = $newX . "," . $point->y . "," . $point->z;

							if (!in_array($newPoint, $visited) && !in_array($newPoint, $queue))
							{
								$queue[] = $newPoint;
							}
						}
					}
				}

				$newYs = [
					$point->y + 1,
					$point->y - 1
				];

				foreach ($newYs as $newY)
				{
					if ($newY > $this->dimensions->y->min - 1 && $newY <= $this->dimensions->y->max + 1)
					{
						$check = $this->check($point->x, $newY, $point->z);

						if ($check === true)
						{
							$result->part2++;
						}
						else
						{
							$newPoint = $point->x . "," . $newY . "," . $point->z;

							if (!in_array($newPoint, $visited) && !in_array($newPoint, $queue))
							{
								$queue[] = $newPoint;
							}
						}
					}
				}

				$newZs = [
					$point->z + 1,
					$point->z - 1
				];

				foreach ($newZs as $newZ)
				{
					if ($newZ > $this->dimensions->z->min - 1 && $newZ <= $this->dimensions->z->max + 1)
					{
						$check = $this->check($point->x, $point->y, $newZ);

						if ($check === true)
						{
							$result->part2++;
						}
						else
						{
							$newPoint = $point->x . "," . $point->y . "," . $newZ;

							if (!in_array($newPoint, $visited) && !in_array($newPoint, $queue))
							{
								$queue[] = $newPoint;
							}
						}
					}
				}
			}

			return $result;
		}
	}
?>

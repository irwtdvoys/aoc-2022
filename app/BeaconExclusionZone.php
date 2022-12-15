<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use AoC\Utils\Range;
	use App\BeaconExclusionZone\Sensor;

	class BeaconExclusionZone extends Helper
	{
		/** @var Sensor[] */
		public array $sensors;
		/** @var Position2d[] */
		public array $beacons;

		public int $target;
		public int $max;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->sensors = array_map(
				function ($element)
				{
					preg_match_all("/-?\d+/", $element, $matches);
					$values = array_map("intval", $matches[0]);
					$beacon = new Position2d($values[2], $values[3]);
					$this->beacons[] = $beacon;

					return new Sensor(new Position2d($values[0], $values[1]), $beacon);
				},
				explode(PHP_EOL, $raw)
			);

			$this->target = (count($this->sensors) === 14) ? 10 : 2000000;
			$this->max = (count($this->sensors) === 14) ? 20 : 4000000;
		}

		private function checkRow($y): array
		{
			$ranges = [];

			foreach ($this->sensors as $sensor)
			{
				$distance = $sensor->distance($sensor->position->x, $y);

				if ($distance <= $sensor->range)
				{
					$remainder = $sensor->range - $distance;
					$range = new Range($sensor->position->x - $remainder, $sensor->position->x + $remainder);

					$ranges[] = $range;
				}
			}


			#dump($ranges);
			$count = null;

			while ($count !== 0)
			{
				$count = 0;

				for ($loop = 0; $loop < count($ranges); $loop++)
				{
					$next = array_shift($ranges);

					foreach ($ranges as $range)
					{
						if ($range->intersects($next))
						{
							$range->add($next->min);
							$range->add($next->max);
							$count++;
							continue 2;
						}
					}

					$ranges[] = $next;
				}
			}

			return $ranges;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$y = $this->target;
			$ranges = $this->checkRow($this->target);

			// calculate cells on the row containing a beacon
			$beacons = array_unique(array_map(
				function ($element)
				{
					return $element->x;
				},
				array_filter(
					$this->beacons,
					function ($beacon) use ($y)
					{
						return $beacon->y === $y;
					}
				)
			));

			// Remove beacon count from result if they fall within range
			foreach ($beacons as $x)
			{
				foreach ($ranges as $range)
				{
					if ($range->contains($x))
					{
						$result->part1--;
						break;
					}
				}
			}

			foreach ($ranges as $range)
			{
				$result->part1 += $range->count();
			}


			for ($y = 0; $y <= $this->max; $y++)
			{
				$ranges = $this->checkRow($y);

				if (count($ranges) === 2)
				{
					$x = ($ranges[0]->max + 2 === $ranges[1]->min) ? $ranges[0]->max + 1 : $ranges[1]->max + 1;

					$result->part2 = ($x * 4000000) + $y;
					break;
				}
			}



			return $result;
		}
	}
?>

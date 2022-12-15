<?php
	namespace App\BeaconExclusionZone;

	use AoC\Utils\Position2d;

	class Sensor
	{
		public Position2d $position;
		public int $range;

		public function __construct(Position2d $position, Position2d $beacon)
		{
			$this->position = $position;
			$this->range = $this->distance($beacon->x, $beacon->y);
		}

		public function distance(int $x, int $y): int
		{
			return abs($this->position->x - $x) + abs($this->position->y - $y);
		}
	}
?>

<?php
	namespace App\HillClimbingAlgorithm;

	use AoC\Utils\Position2d;

	class Cell
	{
		public int $value;
		public Position2d $position;
		public int $shortest;

		public function __construct(int $x, int $y, int $value, int $shortest = PHP_INT_MAX)
		{
			$this->value = $value;
			$this->position = new Position2d($x, $y);
			$this->shortest = $shortest;
		}
	}
?>

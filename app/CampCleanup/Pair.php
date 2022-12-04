<?php
	namespace App\CampCleanup;

	use AoC\Utils\Range;

	class Pair
	{
		public array $ranges = [];

		public function __construct(string $data)
		{
			preg_match('/(\d+)-(\d+),(\d+)-(\d+)/', $data, $matches);

			$this->ranges = [
				new Range((int)$matches[1], (int)$matches[2]),
				new Range((int)$matches[3], (int)$matches[4])
			];
		}

		public function contained(): bool
		{
			return (
				$this->ranges[0]->min >= $this->ranges[1]->min &&
				$this->ranges[0]->max <= $this->ranges[1]->max
			) || (
				$this->ranges[1]->min >= $this->ranges[0]->min &&
				$this->ranges[1]->max <= $this->ranges[0]->max
			);
		}

		public function overlaps(): bool
		{
			return (
				$this->ranges[1]->min <= $this->ranges[0]->max &&
				$this->ranges[1]->max >= $this->ranges[0]->min
			) || (
				$this->ranges[0]->min <= $this->ranges[1]->max &&
				$this->ranges[0]->max >= $this->ranges[1]->min
			);
		}
	}
?>

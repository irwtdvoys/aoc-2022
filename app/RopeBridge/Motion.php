<?php
	namespace App\RopeBridge;

	class Motion
	{
		public string $direction;
		public int $distance;

		public function __construct($direction, int $distance)
		{
			$this->direction = $direction;
			$this->distance = $distance;
		}

		public function __toString(): string
		{
			return $this->direction . " " . $this->distance;
		}
	}
?>

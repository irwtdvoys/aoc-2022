<?php
	namespace App\RopeBridge;

	use AoC\Utils\Range;

	class Ranges
	{
		public Range $x;
		public Range $y;

		public function __construct()
		{
			$this->x = new Range(0, 0);
			$this->y = new Range(0, 0);
		}
	}
?>

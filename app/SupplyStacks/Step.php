<?php

	namespace App\SupplyStacks;

	class Step
	{
		public int $move = 0;
		public int $from = 0;
		public int $to = 0;

		public function __construct(string $data)
		{
			preg_match("/move (?'move'\d+) from (?'from'\d) to (?'to'\d)/", $data, $matches);

			$this->move = $matches['move'];
			$this->from = $matches['from'];
			$this->to = $matches['to'];
		}
	}
?>

<?php
	namespace App\RucksackReorganization;

	class Rucksack
	{
		public array $compartments = [];

		public function __construct($data)
		{
			$split = str_split($data);

			$this->compartments = array_chunk($split, count($split) / 2);
		}

		public function both(): array
		{
			return array_values(array_unique(array_intersect($this->compartments[0], $this->compartments[1])));
		}

		public function all(): array
		{
			return array_merge(...$this->compartments);
		}
	}
?>

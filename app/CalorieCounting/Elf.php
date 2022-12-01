<?php
	namespace App\CalorieCounting;

	use Bolt\Base;

	class Elf extends Base
	{
		/** @var int[] */
		public array $food = [];

		public function __construct(string|array $data)
		{
			$this->food = !is_array($data) ? array_map("intval", explode(PHP_EOL, $data)) : $data;
		}

		public function total(): int
		{
			return array_sum($this->food);
		}
	}
?>

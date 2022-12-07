<?php
	namespace App\NoSpaceLeftOnDevice;

	abstract class Item
	{
		public string $name;

		abstract public function size(): int;
		abstract public function list(int $depth = 0): void;
	}
?>

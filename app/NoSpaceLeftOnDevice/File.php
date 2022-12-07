<?php
	namespace App\NoSpaceLeftOnDevice;

	class File extends Item
	{
		public int $size;

		public function __construct(string $name, int $size)
		{
			$this->name = $name;
			$this->size = $size;
		}

		public function size(): int
		{
			return $this->size;
		}

		public function list(int $depth = 0): void
		{
			echo(str_repeat(" ", $depth * 2) . "- " . $this->name  . " (file, size=" . $this->size . ")" . PHP_EOL);
		}
	}
?>

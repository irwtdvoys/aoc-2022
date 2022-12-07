<?php
	namespace App\NoSpaceLeftOnDevice;

	class Directory extends Item
	{
		public string $name;
		/** @var Item[] */
		public array $children = [];
		public ?Directory $parent;

		public function __construct(string $name, ?Directory $parent = null)
		{
			$this->name = $name;
			$this->parent = $parent;
		}

		public function size(): int
		{
			$result = 0;

			foreach ($this->children as $child)
			{
				$result += $child->size();
			}

			return $result;
		}

		public function add(Item $item): self
		{
			$this->children[] = $item;

			return $this;
		}

		public function list(int $depth = 0): void
		{
			echo(str_repeat(" ", $depth * 2) . "- " . $this->name  . " (dir)" . PHP_EOL);

			foreach ($this->children as $child)
			{
				$child->list($depth + 1);
			}
		}
	}
?>

<?php
	namespace App\MonkeyInTheMiddle;

	class Monkey
	{
		public int $count = 0;
		public array $items = [];

		public string $operation;
		public int $test; // dividible by
		public array $targets = [];

		public function __construct(string $data)
		{
			$lines = array_map(
				function ($element)
				{
					return trim($element);
				},
				explode(PHP_EOL, $data)
			);

			$this->items = array_map("intval", explode(", ", substr($lines[1], 16)));
			$this->operation = substr($lines[2], 17);
			$this->test = substr($lines[3], strrpos($lines[3], " "));
			$this->targets = [
				0 => (int)$lines[5][strlen($lines[5]) - 1],
				1 => (int)$lines[4][strlen($lines[4]) - 1],
			];
		}

		public function inspect(bool $reduceWorry = true): ?int
		{
			$item = array_shift($this->items);

			if ($item === null)
			{
				return null;
			}

			$this->count++;

			$new = eval("return " . str_replace("old", $item, $this->operation) . ";");

			$bored = floor($new / ($reduceWorry ? 3 : 1));

			return $bored;
		}

		public function target(int $value): int
		{
			return $this->targets[($value % $this->test) === 0];
		}

		public function catches(int $item): self
		{
			$this->items[] = $item;

			return $this;
		}
	}
?>

<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\CircularLinkedList;
	use AoC\Utils\LinkedList\Node;

	class GrovePositioningSystem extends Helper
	{
		public CircularLinkedList $list;
		/** @var Node[] */
		public array $order;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$data = array_map("intval", explode(PHP_EOL, $raw));

			$this->list = new CircularLinkedList();

			foreach ($data as $next)
			{
				$this->list->push($next);
				$this->order[] = $this->list->current()->previous;
			}
		}

		private function draw(): void
		{
			$pointer = $this->list->first;

			$results = [];

			for ($loop = 0; $loop < $this->list->count(); $loop++)
			{
				$results[] = $pointer->data;
				$pointer = $pointer->next;
			}

			echo(implode(", ", $results) . PHP_EOL);
		}

		private function mix(): void
		{
			foreach ($this->order as $node)
			{
				if ($node->data === 0)
				{
					continue;
				}

				$node->previous->next = $node->next;
				$node->next->previous = $node->previous;
				$max = $node->data % (count($this->order) - 1);

				switch (true)
				{
					case $node->data > 0:
						$target = $node->next;

						for ($loop = 1; $loop <= abs($max); $loop++)
						{
							$target = $target->next;
						}

						$node->next = $target;
						$node->previous = $target->previous;
						$target->previous->next = $node;
						$target->previous = $node;

						break;
					case $node->data < 0:
						$target = $node->previous;

						for ($loop = 1; $loop <= abs($max); $loop++)
						{
							$target = $target->previous;
						}

						$node->previous = $target;
						$node->next = $target->next;
						$target->next->previous = $node;
						$target->next = $node;

						break;
				}

				if ($this->verbose)
				{
					$this->draw();
				}
			}
		}

		private function results(): int
		{
			$count = count($this->order);
			$targets = [1000 % $count, 2000 % $count, 3000 % $count];

			$result = 0;

			while ($this->list->current()->data !== 0)
			{
				$this->list->next();
			}

			for ($loop = 1; $loop <= $this->list->count() ; $loop++)
			{
				$this->list->next();

				if (in_array($loop, $targets))
				{
					$result += $this->list->current()->data;
				}
			}

			return $result;
		}

		private function reset(): void
		{
			$count = count($this->order);

			for ($index = 0; $index < count($this->order); $index++)
			{
				$this->order[$index]->next = $this->order[($index + 1) % $count];
				$this->order[$index]->previous = $this->order[$index === 0 ? $count - 1 : ($index - 1) % $count];
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$this->mix();
			$result->part1 = $this->results();

			$this->reset();

			foreach ($this->order as $item)
			{
				$item->data *= 811589153;
			}

			for ($loop = 0; $loop < 10; $loop++)
			{
				$this->mix();
			}

			$result->part2 = $this->results();

			return $result;
		}
	}
?>

<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\NoSpaceLeftOnDevice\Directory;
	use App\NoSpaceLeftOnDevice\File;

	class NoSpaceLeftOnDevice extends Helper
	{
		public Directory $fileSystem;
		public Directory $pointer;
		public int $index = 0;
		public array $lines = [];
		/** @var Directory[] */
		public array $directories = [];

		public const MAX_DISK_SPACE = 70000000;
		public const SPACE_REQUIRED = 30000000;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($this->filename($override));
			$this->lines = explode(PHP_EOL, $raw);

			$this->fileSystem = new Directory("/");
			$this->fileSystem->parent = $this->fileSystem;
		}

		private function cd(string $location): void
		{
			switch ($location)
			{
				case "/":
					$this->pointer = $this->fileSystem;
					break;
				case "..":
					$this->pointer = $this->pointer->parent;
					break;
				default:
					foreach ($this->pointer->children as $child)
					{
						if ($child instanceof Directory && $child->name === $location)
						{
							$this->pointer = $child;
							break;
						}
					}
					break;
			}
		}

		private function ls(): void
		{
			$this->index++;

			while ($this->index < count($this->lines) && $this->currentLine()[0] !== "$")
			{
				$line = $this->currentLine();

				if (str_starts_with($line, "dir"))
				{
					$new = new Directory(substr($line, 4), $this->pointer);
				}
				else
				{
					[$size, $name] = explode(" ", $line);
					$new = new File($name, $size);
				}

				$this->pointer->add($new);
				$this->index++;
			}

			$this->index--;
		}

		private function currentLine(): string
		{
			return $this->lines[$this->index];
		}

		private function scan(Directory $directory): void
		{
			$this->directories[] = $directory;

			foreach ($directory->children as $child)
			{
				if ($child instanceof Directory)
				{
					$this->scan($child);
				}
			}
		}

		public function run(): Result
		{
			$result = new Result(0, PHP_INT_MAX);

			while ($this->index < count($this->lines))
			{
				$line = $this->currentLine();

				if ($line[0] === "$")
				{
					$parts = explode(" ", $line);
					$this->{$parts[1]}($parts[2] ?? null);
				}

				$this->index++;
			}

			if ($this->verbose)
			{
				$this->fileSystem->list();
			}

			$this->scan($this->fileSystem);

			$target = self::SPACE_REQUIRED - (self::MAX_DISK_SPACE - $this->fileSystem->size());

			foreach ($this->directories as $directory)
			{
				$size = $directory->size();

				if ($size <= 100000)
				{
					$result->part1 += $size;
				}

				if ($size >= $target)
				{
					$result->part2 = min($result->part2, $size);
				}
			}

			return $result;
		}
	}
?>

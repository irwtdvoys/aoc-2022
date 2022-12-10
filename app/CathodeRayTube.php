<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use App\CathodeRayTube\Instruction;

	class CathodeRayTube extends Helper
	{
		private const DISPLAY_X = 40;
		private const DISPLAY_Y = 6;

		/** @var Instruction[] */
		private array $program;
		private int $x = 1;
		private int $cycle = 1;
		/** @var bool[][] */
		private array $display; //

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->program = array_map(
				function ($element)
				{
					return new Instruction($element);
				},
				explode(PHP_EOL, $raw)
			);

			$column = array_fill(0, self::DISPLAY_Y, false);
			$this->display = array_fill(0, self::DISPLAY_X, $column);
		}

		private function signalStrength(): int
		{
			return $this->cycle * $this->x;
		}

		private function draw(): void
		{
			for ($y = 0; $y < self::DISPLAY_Y; $y++)
			{
				for ($x = 0; $x < self::DISPLAY_X; $x++)
				{
					echo($this->display[$x][$y] ? "█" : " ");
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		private function coords(): Position2d
		{
			$y = floor($this->cycle / self::DISPLAY_X);
			$x = ($this->cycle - 1) - ($y * self::DISPLAY_X);

			return new Position2d($x, $y);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$index = 0;
			$work = [];

			foreach ($this->program as $instruction)
			{
				switch ($instruction->operation)
				{
					case "noop":
						$index++;
						break;
					case "addx":
						$index = $index + 2;
						$work[$index] = $instruction->value;
						break;
				}
			}

			$intervals = [20, 60, 100, 140, 180, 220];

			while ($this->cycle < (self::DISPLAY_X * self::DISPLAY_Y))
			{
				if (in_array($this->cycle, $intervals))
				{
					$result->part1 += $this->signalStrength();
				}

				$coords = $this->coords();

				if ($this->verbose)
				{
					echo(PHP_EOL . "#" . $this->cycle . " [" . $this->x . "]");
				}

				if ($coords->x >= ($this->x - 1) && $coords->x <= ($this->x + 1))
				{
					$this->display[$coords->x][$coords->y] = true;
				}

				if (isset($work[$this->cycle]))
				{
					if ($this->verbose)
					{
						echo(" addx " . $work[$this->cycle]);
					}

					$this->x += $work[$this->cycle];
				}

				$this->cycle++;
			}

			if ($this->verbose)
			{
				echo(PHP_EOL);
			}

			$this->draw();

			return $result;
		}
	}
?>

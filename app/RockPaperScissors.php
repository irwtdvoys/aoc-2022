<?php
	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\RockPaperScissors\Outcomes;
	use App\RockPaperScissors\Shapes;
	use Exception;

	class RockPaperScissors extends Helper
	{
		/** @var string[][] */
		public array $strategy;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->strategy = array_map(
				function ($element)
				{
					return explode(" ", $element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		private function play(Shapes $opponent, Shapes $player): int
		{
			$score = $player->value;

			switch (true)
			{
				case $player === $opponent:
					$score += 3;
					break;
				case $player === Shapes::Rock && $opponent === Shapes::Scissors:
				case $player === Shapes::Paper && $opponent === Shapes::Rock:
				case $player === Shapes::Scissors && $opponent === Shapes::Paper:
					$score += 6;
					break;
			}

			return $score;
		}

		private function calculate(Shapes $opponent, Outcomes $outcome): Shapes
		{
			switch ($outcome)
			{
				case Outcomes::Win:
					switch ($opponent)
					{
						case Shapes::Rock:
							$result = Shapes::Paper;
							break;
						case Shapes::Paper:
							$result = Shapes::Scissors;
							break;
						case Shapes::Scissors:
							$result = Shapes::Rock;
							break;
					}
					break;
				case Outcomes::Loss:
					switch ($opponent)
					{
						case Shapes::Rock:
							$result = Shapes::Scissors;
							break;
						case Shapes::Paper:
							$result = Shapes::Rock;
							break;
						case Shapes::Scissors:
							$result = Shapes::Paper;
							break;
					}
					break;
				case Outcomes::Draw:
					$result = $opponent;
					break;
			}

			return $result;
		}

		private function asShape(string $value): Shapes
		{
			return match ($value)
			{
				"A", "X" => Shapes::Rock,
				"B", "Y" => Shapes::Paper,
				"C", "Z" => Shapes::Scissors,
				default => throw new Exception("Unknown shape lookup value"),
			};
		}

		private function asOutcome(string $value): Outcomes
		{
			return match ($value)
			{
				"X" => Outcomes::Loss,
				"Y" => Outcomes::Draw,
				"Z" => Outcomes::Win,
				default => throw new Exception("Unknown outcome lookup value"),
			};
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->strategy as [$opponent, $player])
			{
				$opponent = $this->asShape($opponent);

				$result->part1 += $this->play($opponent, $this->asShape($player));
				$result->part2 += $this->play($opponent, $this->calculate($opponent, $this->asOutcome($player)));
			}

			return $result;
		}
	}
?>

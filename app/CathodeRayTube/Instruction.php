<?php
	namespace App\CathodeRayTube;

	class Instruction
	{
		public string $operation;
		public ?int $value = null;

		public function __construct(string $data)
		{
			$parts = str_split($data, 4);
			$this->operation = $parts[0];

			if (isset($parts[1]))
			{
				$this->value = (int)trim($parts[1]);
			}
		}
	}
?>

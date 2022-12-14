<?php
	namespace App\RegolithReservoir;

	enum Tile: string
	{
		case Wall = "█";
		case Empty = " ";
		case Sand = "o";
		case Entry = "+";
	}
?>

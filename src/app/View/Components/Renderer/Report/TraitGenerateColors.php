<?php

namespace App\View\Components\Renderer\Report;

trait TraitGenerateColors
{

	public function generateColors($length) {
		$baseColors = [
			'#4dc9f6', '#f67019', '#f53794',
			'#537bc4', '#acc236', '#166a8f',
			'#00a950', '#58595b', '#8549ba'
		];
		$colors = [];
	
		for ($i = 0; $i < $length; $i++) {
			$index = $i % count($baseColors);
			if ($i >= count($baseColors)) {
				// Generate a new random color
				$newColor = $this->generateRandomColor();
				$baseColors[] = $newColor;
			}
			$colors[] = $baseColors[$index];
		}
	
		return $colors;
	}
	
	private function generateRandomColor() {
		$letters = '0123456789ABCDEF';
		$color = '#';
	
		// Generate a random hex color code
		for ($i = 0; $i < 6; $i++) {
			$color .= $letters[random_int(0, 15)];
		}
	
		return $color;
	}

}

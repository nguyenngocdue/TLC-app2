<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\StringReport;

trait TraitLabelChart
{
    public function makeLabels($data, $widgets){
		$params = $widgets['params'];
		if (isset($params['map_labels'])){
			$fields = $params['map_labels'];
			$array = array_map(function($items) use ($fields) {
				$newLabel = implode('.', array_map(function($field) use ($items) {
					return is_object($items[$field]) ? $items[$field]->value : $items[$field];
				}, $fields));
				$newLabel = preg_replace('/\./', ' (', $newLabel, 1) .')';
				return $newLabel;
			}, $data);
			return StringReport::arrayToJsonWithSingleQuotes($array);
		}
        // dd($widgets);
		$labelFields = $widgets['params']['label_meta_data_1'];
		$labels = array_column($data, $labelFields);
		return  StringReport::arrayToJsonWithSingleQuotes($labels);
	}

}

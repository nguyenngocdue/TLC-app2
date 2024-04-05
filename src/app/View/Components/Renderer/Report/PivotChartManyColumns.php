<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\Widgets\WidgetGroup;
use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Illuminate\View\Component;

class PivotChartManyColumns extends Component
{


	public function __construct(
		private $key = '',
		private $data = [],
		private $optionPrint = '',
		private $paramFilters = [],
	) {
	}

	private function makeObject($allWidgets)
	{
		$allWidgets['dimensions'] = (array)json_decode($allWidgets['dimensions']);
		$allWidgets['params'] = StringReport::separateStringsByDot((array)json_decode($allWidgets['params']));
		return $allWidgets;
	}

	private function generateValueSForDimensions($widgets, $params,)
	{
		return array_merge($params, $widgets['dimensions'] ?? []);;
	}

	private function makeLabels($data, $widgets)
	{
		$params = $widgets['params'];
		if (isset($params['map_labels'])) {
			$fields = $params['map_labels'];
			$array = array_map(function ($items) use ($fields) {
				$newLabel = implode('.', array_map(function ($field) use ($items) {
					return is_object($items[$field]) ? $items[$field]->value : $items[$field];
				}, $fields));
				$newLabel = preg_replace('/\./', ' (', $newLabel, 1) . ')';
				return $newLabel;
			}, $data);
			return StringReport::arrayToJsonWithSingleQuotes($array);
		}
		$labelFields = $widgets['params']['labels'];
		$labels = array_column($data, $labelFields);
		return  StringReport::arrayToJsonWithSingleQuotes($labels);
	}

	private function makeDataSource($dataSource, $key, $paramFilters)
	{
		$allWidgets = LibWidgets::getAll();
		$widgets = $allWidgets[$key];
		$widgets =  $this->makeObject($widgets);

		if ($this->optionPrint === 'portrait') {
			$widgets['dimensions']['width'] = $widgets['dimensions']['width'] * 0.6;
		}
		/*
			+ map_label: to create label under each column (ex: STW1(PPR Guide Plate))
			+ override_meta_name...: 
					1. override the legend name to be equal to the value of a filed input from the admin
					2. You can set any sequence you want
		*/
		if ($widgets['chart_type'] === 'bar_many_columns') {
			// dd($paramFilters,$widgets, $dataSource);
			$itemsInParams = $widgets['params'];
			$lenItemsInParams = count($itemsInParams);

			$items = Report::convertToType($dataSource);
			if (empty($items)) return [];

			$dataOfParamsCol = [];
			$count = 0;
			$max = 0;
			for ($i = 0; $i < $lenItemsInParams; $i++) {
				if (isset($itemsInParams['meta_data_' . $i])) {
					$fieldMeta = $widgets['params']['meta_data_' . $i];
					$dataOfParamsCol[$fieldMeta]['data'] =  array_column($items, $fieldMeta);
					$x = $dataOfParamsCol[$fieldMeta]['data'];
					// count
					$c = count($x);
					if ($c > $count) $count = $c;
					// max 
					$m = max($x);
					if ($m > $max) $max = $m;

					$_field = 'override_meta_name' . $i;
					if (isset($itemsInParams[$_field]) && isset($paramFilters[$itemsInParams[$_field]])) {
						$dataOfParamsCol[$fieldMeta]['label'] = $paramFilters[$itemsInParams[$_field]];
					} else {
						$dataOfParamsCol[$fieldMeta]['label'] = $itemsInParams[$_field];
					}
					$dataOfParamsCol[$fieldMeta] = (object)$dataOfParamsCol[$fieldMeta];
				}
			}

			$labels = $this->makeLabels($items, $widgets);
			$meta['numbers'] = array_values($dataOfParamsCol);

			$meta['labels'] = $labels;
			$meta['count'] = $count;
			$meta['max'] = $max;
			$metric = [];
		}
		// Set data for widget
		$widgetData =  [
			"title_a" => "title_a" . $key,
			"title_b" => "title_b" . $key,
			'meta' => $meta,
			'metric' => $metric,
			'chart_type' => $widgets['chart_type'],
			'titleChart' => '',
			'dimensions' => $widgets['dimensions'],
		];
		// dd($widgetData);
		return $widgetData;
	}


	public function render()
	{
		$dataWidgets = $this->makeDataSource($this->data, $this->key, $this->paramFilters);
		// dd($this->paramFilters);
		return view("components.renderer.report.pivot-chart-many-columns", [
			'dataWidgets' => $dataWidgets,
			'key' => $this->key,
			'optionPrint' => $this->optionPrint
		]);
	}
}

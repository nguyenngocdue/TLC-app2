<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\Widgets\WidgetGroup;
use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Illuminate\View\Component;

class PivotChart3 extends Component
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
			+ if you want to change name of data source to displayed chart's legend on frontend: 
				in the Params Column : overrideDataName1: "the filed name you want to set for the datasource (meta_data_1, meta_data_2) '
			+ map_label: to create label under each column (ex: STW1(PPR Guide Plate))

		*/
		if ($widgets['chart_type'] === 'bar_two_columns') {
			// dd($paramFilters,$widgets, $dataSource);
			$paramLeftCol = $widgets['params']['meta_data_1'];
			$paramRightCol = $widgets['params']['meta_data_2'];
			$dataName1 = $widgets['dimensions']['dataName1'];
			$dataName2 = $widgets['dimensions']['dataName2'];

			$items = Report::convertToType($dataSource);
			$previousAcceptances = array_column($items, $paramLeftCol);
			$latestAcceptances = array_column($items, $paramRightCol);
			$labels = $this->makeLabels($items, $widgets);
			$meta['numbers'] = [
				(object)[
					'label' => $paramFilters[$widgets['params']['overrideDataName1']] ?? $dataName1,
					'data' => $latestAcceptances
				],
				(object)[
					'label' => $paramFilters[$widgets['params']['overrideDataName2']] ?? $dataName2,
					'data' => $previousAcceptances
				]
			];
			$meta['labels'] = $labels;
			$meta['count'] = count($latestAcceptances);
			$meta['max'] = empty($latestAcceptances) ? 0 : max($latestAcceptances);
			$metric = [];
		} else {
			$labels = StringReport::arrayToJsonWithSingleQuotes(array_keys($dataSource));
			$numbers = StringReport::arrayToJsonWithSingleQuotes(array_values($dataSource));
			$max = max(array_values($dataSource));
			$count = count($dataSource);
			$meta = [
				'numbers' => $numbers,
				'labels' => $labels,
				'max' => $max,
				'count' => $count
			];
			// information for metric data
			$metric = [];
			array_walk($dataSource, function ($value, $key) use (&$metric) {
				return $metric[] = (object) [
					'meter_id' => $key,
					'metric_name' => $value
				];
			});
			// related to dimensions AxisX and AxisY
			$params = [
				'height' => $max / 2 * 30,
				'scaleMaxX' => $max * 2,
				'scaleMaxY' => $max * 2,
			];
			$widgets = $this->generateValueSForDimensions($widgets, $params);
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
		return view("components.renderer.report.pivot-chart3", [
			'dataWidgets' => $dataWidgets,
			'key' => $this->key,
			'optionPrint' => $this->optionPrint
		]);
	}
}

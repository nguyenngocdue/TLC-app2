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
	) {
	}

	private function makeObject($allWidgets)
	{
		$allWidgets['dimensions'] = (array)json_decode($allWidgets['dimensions']);
		$allWidgets['params'] = (array)json_decode($allWidgets['params']);;
		return $allWidgets;
	}

	private function generateValueSForDimensions($widgets, $params,)
	{
		foreach ($params as $key => $param) {
			if (!isset($widgets['dimensions'][$key])) {
				$widgets['dimensions'][$key] = $params[$key];
			}
		}
		return $widgets;
	}

	private function makeDataSource($dataSource, $key)
	{

		$allWidgets = LibWidgets::getAll();
		$widgets = $allWidgets[$key];
		$widgets =  $this->makeObject($widgets);

		if ($widgets['chart_type'] === 'bar_two_columns') {
			// dd($widgets, $dataSource);
			$paramLeftCol = $widgets['params']['meta_data_1'];
			$paramRightCol = $widgets['params']['meta_data_2'];
			$labelFields = $widgets['params']['labels'];
			$dataName1 = $widgets['dimensions']['dataName1'];
			$dataName2 = $widgets['dimensions']['dataName2'];

			$items = Report::convertToType($dataSource);
			$previousAcceptances = array_column($items, $paramLeftCol);
			$latestAcceptances = array_column($items, $paramRightCol);
			$labels = array_column($items, $labelFields);
			$labels = StringReport::arrayToJsonWithSingleQuotes($labels);
			$meta['numbers'] = [
				(object)[
					'label' => $dataName1,
					'data' => $latestAcceptances
				],
				(object)[
					'label' => $dataName2,
					'data' => $previousAcceptances
				]
			];
			$meta['labels'] = $labels;
			$meta['count'] = count($latestAcceptances);
			$meta['max'] = max($latestAcceptances);
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
			'chartType' => $widgets['chart_type'],
			'titleChart' => '',
			'dimensions' => $widgets['dimensions'],
		];
		return $widgetData;
	}


	public function render()
	{
		$dataWidgets = $this->makeDataSource($this->data, $this->key);
		// dd($this->key);
		return view("components.renderer.report.pivot-chart3", [
			'dataWidgets' => $dataWidgets,
			'key'=>$this->key,
		]);
	}
}

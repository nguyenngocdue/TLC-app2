<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\Widgets\WidgetGroup;
use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\StringReport;
use Illuminate\View\Component;

class PivotChart2 extends Component
{


    public function __construct(
        private $key = '',
        private $data = [],
    ) {
    }

    private function makeDimensions($allWidgets){
        $dimensions = json_decode($allWidgets['dimensions']);
        $allWidgets['dimensions'] = (array)$dimensions;
        return $allWidgets;
    }

	private function generateValueSForDimensions($widgets, $params, ){
		foreach ($params as $key => $param){
			if(!isset($widgets['dimensions'][$key])){
				$widgets['dimensions'][$key] = $params[$key];
			}
		}
		return $widgets;
	}

    private function makeDataSource($dataSource,$key){

        $allWidgets = LibWidgets::getAll();
        $widgets = $allWidgets[$key];
        $widgets =  $this->makeDimensions($widgets);

        $labels = StringReport::arrayToJsonWithSingleQuotes(array_keys($dataSource));
		$numbers = StringReport::arrayToJsonWithSingleQuotes(array_values($dataSource));
		$max = max(array_values($dataSource));
		$count = count($dataSource);
		$meta = [
			'labels' => $labels,
			'numbers' => $numbers,
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
			'height' => $max/2*30,
			'scaleMaxX' => $max*2,
			'scaleMaxY' => $max*2,
		];
		$widgets = $this->generateValueSForDimensions($widgets, $params);
		
		// Set data for widget
		$widgetData =  [
			"title_a" => "title_a".$key,
			"title_b" => "title_b".$key,
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
        return view("components.renderer.report.pivot-chart2", [
            'dataWidgets' => $dataWidgets,
        ]);
    }
}

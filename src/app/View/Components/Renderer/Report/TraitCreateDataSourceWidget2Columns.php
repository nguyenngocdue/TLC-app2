<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

trait TraitCreateDataSourceWidget2Columns
{
    use TraitLabelChart;

    private function generateValueSForDimensions($widgets, $params,)
	{
		return array_merge($params, $widgets['dimensions'] ?? []);;
	}

	private function createDataSourceWidgets2Columns($key, $dataSource, $dataWidgets)
	{
		if($this->optionPrint === 'portrait') {
			$dataWidgets['dimensions']['width'] = $dataWidgets['dimensions']['width']*0.6; 
        }
		// dd($dataWidgets)

		if ($dataWidgets['chart_type'] === 'bar_two_columns') {
			$paramFilters = $dataWidgets['param_filters'];
			$paramLeftCol = $dataWidgets['params']['meta_data_1'];
			$paramRightCol = $dataWidgets['params']['meta_data_2'];
			$dataName1 = isset($dataWidgets['params']['overrideDataName1'])?  $paramFilters[$dataWidgets['params']['overrideDataName1']]: $dataWidgets['dimensions']['dataName1'];
			$dataName2 = isset($dataWidgets['params']['overrideDataName2'])?  $paramFilters[$dataWidgets['params']['overrideDataName2']]: $dataWidgets['dimensions']['dataName2'];

			$items = Report::convertToType($dataSource);
			$previousAcceptances = array_column($items, $paramLeftCol);
			$latestAcceptances = array_column($items, $paramRightCol);
			$labels = $this->makeLabels($items, $dataWidgets);
			$max =  max(array_merge(array_values($previousAcceptances), array_values($latestAcceptances)));
			$meta['numbers'] = [
				(object)[
					'label' =>  $dataName1,
					'data' => $latestAcceptances
				],
				(object)[
					'label' => $dataName2,
					'data' => $previousAcceptances
				]
			];
			$meta['labels'] = $labels;
			$meta['count'] = count($latestAcceptances);
			$meta['max'] = empty($latestAcceptances) ? 0 :max($latestAcceptances);
			$metric = [];
		}
        // related to dimensions AxisX and AxisY
        $params = [
            'height' => $max / 2 * 30,
            'scaleMaxX' => $max * 2,
            'scaleMaxY' => (int)$max + 100,
        ];
        $dataWidgets['dimensions'] = array_merge($params, $dataWidgets['dimensions']);
        // dd($dataWidgets);

      // Set data for widget
		$result =  [
			'key_of_data_source'=> $key,
            'key' => $key.'_'.$dataWidgets['key_md5'],
			"title_a" => "title_a" .'_'. $dataWidgets['key_md5'],
			"title_b" => "title_b" .'_'. $dataWidgets['key_md5'],
			'meta' => $meta,
			'metric' => $metric,
			'chart_type' => $dataWidgets['chart_type'],
			'title_chart' => '',
			'dimensions' => $dataWidgets['dimensions'],
            'key_name' => $dataWidgets['key_name'],
		];

		return $result;
	}



}

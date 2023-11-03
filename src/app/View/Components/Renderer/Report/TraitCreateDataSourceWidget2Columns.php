<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

trait TraitCreateDataSourceWidget2Columns
{
    use TraitLabelChart;

	private function generateColors($length) {
		$baseColors = ['#3237cf', '#250a18', '#cb5698', '#257409', '#acc236', '#868670', '#868670', '#868670', '#868670'];
		$colors = [];
		for ($i = 0; $i < $length; $i++) {
			// Use modulo operator to cycle through base colors if more colors are needed than in the base array
			$baseColorIndex = $i % count($baseColors);
			// Add the selected base color to the colors array
			$colors[] = $baseColors[$baseColorIndex];
		}
		return $colors;
	}

    private function generateValueSForDimensions($widgets, $params,)
	{
		return array_merge($params, $widgets['dimensions'] ?? []);;
	}

	private function getNumbersForWidgets($data, $fieldGetsDataCol, $paramMetaBar, $fieldDataLabel){
		$maxCount = max(array_map('count', array_values($data)));
		$labelData = [];
		$numbers = [];
		for ($i = 0; $i < $maxCount; $i++){
			$result = [];
			$n = 0;
			foreach($data as $key => $values){
				if(isset($values[$i])){
					$result[$n] = $values[$i][$fieldGetsDataCol];
					$labelName = $values[$i][$fieldDataLabel] ?? '';
					if(!in_array($labelName, $labelData)){
						$labelData[] = $labelName;
					}
				} else $result[$n] = 0;
				$n++;
			}
			$numbers[] = $result;
		}
		// dd($numbers);
		$result = [];
		foreach($numbers as $key => $value){
			$result[] = (object)[
				'type' => $paramMetaBar['type'] ?? 'bar',
				'label'=>$labelData[$key],
				'yAxisID'=> $paramMetaBar['y_axis_id'] ?? 'y',
				'data' => $value,
				'backgroundColor'=> $paramMetaBar['backgroundColor'] ?? "#000000" ,
				'borderColor'=> $this->generateColors(count($numbers))[$key],
				'fill'=> $paramMetaBar['fill'] ?? false,
				'type'=> $paramMetaBar['type'] ?? "line",
				'tension'=> $paramMetaBar['tension'] ?? 0,
				'borderWidth'=> $paramMetaBar['borderWidth'] ?? 0.8,
				'pointBackgroundColor'=> $paramMetaBar['pointBackgroundColor'] ?? "#000000",

			];
		}
		return $result;
	}

	private function createDataWidgetForManyColumns($dataSource, $dataWidgets){
		$paramMeta = (array)$dataWidgets['param_meta'];
		foreach ($paramMeta as $type => $paramMetaType){
			$paramMetaType = (array)$paramMetaType;

			$fieldGetsDataCol = $paramMetaType['meta_y_axis'];
	
			// get label for legend's label
			$fieldOfLegendLabel = $paramMetaType['legend_label_meta_x_axis'];
			$dataLegend = array_column($dataSource, $fieldOfLegendLabel);
			$dataLegend = array_unique($dataLegend);
			// dd($dataLegend);
	
			// X axis
			$labelFieldXAxis = $paramMetaType['label_under_col'];
			$labelsXAxis = Report::getValuesByField($dataSource, $labelFieldXAxis);		
			$labelsXAxis = array_unique($labelsXAxis);
			sort($labelsXAxis);
			$strLabelsXAxis = StringReport::arrayToJsonWithSingleQuotes($labelsXAxis);
			
			// Data for each columns
			$groupItemsByXAxis = Report::groupArrayByKey($dataSource,$labelFieldXAxis);
			ksort($groupItemsByXAxis);

			$fieldDataLabel = $paramMetaType['field_data_label'] ?? '';
			$numbers[$type] = $this->getNumbersForWidgets($groupItemsByXAxis, $fieldGetsDataCol, $paramMetaType, $fieldDataLabel);
			
		}
		$valOfColumns = Report::getValuesByField($dataSource, $fieldGetsDataCol);
		$max =  max($valOfColumns);

		//set up data for widget
		$meta['numbers'] = array_merge(...array_values($numbers));
		$meta['labels'] = $strLabelsXAxis;
		$meta['count'] = count($labelsXAxis);
		$meta['max'] = $max;
		$metric = [];

		// related to dimensions X-axis and Y-axis
		$params = [
			'height' => $max / 2 * 30,
			'scaleMaxX' => $max * 2,
			'scaleMaxY' => (int)$max +10,
		];
		$dataWidgets['dimensions'] = array_merge($params, $dataWidgets['dimensions']);
		// dd($dataWidgets);

		// Set data for widget
		$key = 'not yet implemented';
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
		// dd($result);
		return $result;
	}

	private function createDataWidgets2Columns($key, $dataSource, $dataWidgets)
	{
		if($this->optionPrint === 'portrait') {
			$dataWidgets['dimensions']['width'] = $dataWidgets['dimensions']['width']*0.6; 
        }
		// dd($dataWidgets)

		if ($dataWidgets['chart_type'] === 'bar_two_columns') {
			$paramFilters = $dataWidgets['param_filters'];
			$paramLeftCol = $dataWidgets['params']['meta_data_1'] ?? [];
			$paramRightCol = $dataWidgets['params']['meta_data_2'] ?? [];
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
        // related to dimensions X-axis and Y-axis
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

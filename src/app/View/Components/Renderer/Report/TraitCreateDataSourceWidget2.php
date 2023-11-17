<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

trait TraitCreateDataSourceWidget2
{
    use TraitLabelChart;
    use TraitGenerateColors;

	private function getValuesByField($dataSource, $paramLabelCol){
        return array_map(function ($value) use ($paramLabelCol) {
            if(is_object($value[$paramLabelCol])){
                return $value[$paramLabelCol]->value;
            }
            return $value[$paramLabelCol];
        }, $dataSource);
    }



	private function createDataSets($paramMeta, $paramMetaData, $dataManage)
    {
        // dd($dataManage);
        $array = [];
        $indexDataSource = [];
        $numbers = [];
        foreach ($paramMeta as $key => $params) {
            if(isset($paramMetaData[$key])){
                $params = (array)$params;
                $dataSource = $paramMetaData[$key];
                // dd($dataSource, $key);

                $indexDataSource = $dataSource;
                
                $dataSets = [];
                $paramColOnY = $params['meta_y_axis'];
                $dataByParamCol = array_map(fn($item) => (float)$item ,$this->getValuesByField($dataSource,$paramColOnY));
                $numbers = $dataByParamCol;
                
                if($params['chart_type'] === 'bar' || $params['chart_type'] === 'line') {
                    $dataSets['data'] = $dataByParamCol;
                    $dataSets['count'] = count($dataByParamCol);
                    $dataSets['max'] = max($dataByParamCol);
                    $dataSets['type'] = $params['chart_type'];
                    $dataSets['yAxisID'] = $params['y_axis_id'];
                    $dataSets['tension'] = $params['tension'] ?? 0;
                    $dataSets['label'] = $params['data_label'] ?? "undefined";
                    $dataSets['borderWidth'] = $params['borderWidth'] ?? 0;
                    $dataSets['borderColor'] = $params["line_color"] ?? "";
                    $dataSets['pointStyle'] = $params["pointStyle"] ?? "circle";
                    if($params['chart_type'] === 'bar') {
                        $dataSets['backgroundColor'] = $this->generateColors(count($numbers));
                    }
                };
                $array[$key] = (object)$dataSets;
            }
        }
        // get a dataSource to get dimensions
        $indexData =  (array)last($paramMeta);
        $fieldDataLabels = $indexData['field_data_label'];
        $labels = $this->getValuesByField($indexDataSource,$fieldDataLabels);
        $labels = StringReport::arrayToJsonWithSingleQuotes($labels);
        
        $data['datasets'] =  array_values($array);
        $data['labels'] = $labels;
        
        $max = max($numbers);
        // dump($max);
        $dimensions = $dataManage['dimensions'];
        $scaleMaxX = $dimensions['scaleMaxX'] ?? 10;
        // dd($scaleMaxX + $max);
        
        $params = [
            //'height' => $max + $scaleMaxX,
            'scaleMaxX' => (int)($max + $scaleMaxX),
            'scaleMaxY' => (int)($max + $scaleMaxX),
        ];
        $dataManage['dimensions'] = array_merge($dataManage['dimensions'], $params);

        // titleChart
        $titleChart = $dataManage['dimensions']['titleChart'];
        // if(isset($dataManage['params'])){
        //     $paramsManage = $dataManage['params'];
        //     dump($paramMetaData);
        // }


        // dd($dataManage);
      // Set data for widget
        $result =  [
            'key' => $key.'_'.$dataManage['key_md5'],
            "title_a" => "title_a" .'_'. $dataManage['key_md5'],
            "title_b" => "title_b" .'_'. $dataManage['key_md5'],
            'meta' => $data,
            'metric' => [],
            'chart_type' => $dataManage['chart_type'],
            'title_chart' => '',
            'dimensions' => $dataManage['dimensions'],
            'key_name' => $dataManage['key_name'],
            'tempScaleMaxX' => $dataManage['dimensions']['scaleMaxX'],
        ];
        // dd($result);
        return $result;
    }


}

<?php

namespace App\View\Components\Renderer\Report;

use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

trait TraitCreateDataSourceWidget
{
    use TraitLabelChart;
    private function getValuesByField($dataSource, $paramLabelCol)
    {
        return array_map(function ($value) use ($paramLabelCol) {
            if (is_object($value[$paramLabelCol])) {
                return $value[$paramLabelCol]->value;
            }
            return $value[$paramLabelCol];
        }, $dataSource);
    }
    private function createDataSourceWidgets($key, $dataSource, $dataWidgets)
    {
        if ($dataWidgets['option_print'] === 'portrait') {
            $dataWidgets['dimensions']['width'] = $dataWidgets['dimensions']['width'] * 0.6;
        }

        $paramCol = $dataWidgets['params']['meta_data_1'];
        $dataSource = Report::getItemsFromDataSource($dataSource);
        $dataSource = Report::convertToType($dataSource);

        $dataByParamCol = $this->getValuesByField($dataSource, $paramCol);
        $paramLabelCol = $dataWidgets['params']['label_meta_data_1'];
        $dataByParamLabelCol = $this->getValuesByField($dataSource, $paramLabelCol);
        $labels = $this->makeLabels($dataSource, $dataWidgets);
        $numbers = StringReport::arrayToJsonWithSingleQuotes($dataByParamCol);

        $max = (float)max(array_values($dataByParamCol));
        // dump(array_values($dataByParamCol));
        $count = count($dataByParamLabelCol);
        $meta = [
            'numbers' => $numbers,
            'labels' => $labels,
            'max' => $max,
            'count' => $count,
        ];
        // information for metric data
        $metric = [];

        //add label for XAxis
        $fieldOfUnit = $dataWidgets['params']['index_unit']  ?? "";
        $prefixUnit = $dataWidgets['params']['prefix_unit_text']  ?? "";
        $suffixUnit = $dataWidgets['params']['suffix_unit_text']  ?? "";
        $middleUnit = isset(last($dataSource)[$fieldOfUnit]) ? last($dataSource)[$fieldOfUnit] : "";
        $dataWidgets['dimensions']['titleY'] = $prefixUnit . $middleUnit . $suffixUnit;
        // related to dimensions AxisX and AxisY
        $params = [
            'height' => $max / 2 * 30,
            'scaleMaxX' => $max * 2,
            'scaleMaxY' => $max * 2,
        ];
        $dataWidgets['dimensions'] = array_merge($params, $dataWidgets['dimensions']);
        // dd($dataWidgets);
        // Set data for widget
        $result =  [
            'key' => $key . '_' . $dataWidgets['key_md5'],
            "title_a" => "title_a" . '_' . $dataWidgets['key_md5'],
            "title_b" => "title_b" . '_' . $dataWidgets['key_md5'],
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
}

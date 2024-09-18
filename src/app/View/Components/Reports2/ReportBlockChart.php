<?php

namespace App\View\Components\Reports2;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlockChart extends Component
{
    use TraitReportQueriedData;
    
    protected $SERIES_VARIABLE = '{%series%}';

    public function __construct(
        private $block = null,
        private $queriedData = null,
        private $headerCols = [],
        private $transformedFields = [],
    ) {}

    private function changeToJsonOptionsByTransformation($options, $queriedData, $fields){
        $series = [];
        foreach($fields as $name) {
            $data = $queriedData->pluck($name)->toArray();
            $data = array_map(function($item) {
                if(!$item) return null;
                return (float)$item;
            }, $data);
            $series[] = [
                'name' => $name,
                'data' => $data
            ];
        }
        $seriesJson = json_encode($series);
        $options = str_replace($this->SERIES_VARIABLE, $seriesJson, $options);
        return $options;
    }

    private function changeToJsonOptions($options, $queriedData)
    {
        $transformedFields = $this->transformedFields;
        if($transformedFields) {
            $options = $this->changeToJsonOptionsByTransformation($options, $queriedData, $transformedFields);
        } else {
             $parsedVariables = $this->parseVariables($options);
            foreach (last($parsedVariables) as $key => $value) {
                $keyInDta = trim(str_replace('$', '', $value));
                $valueInData = $queriedData->pluck($keyInDta)->toArray();
                $valueInData = '['. implode(',' , array_map(fn($item) => "\"{$item}\"",$valueInData)) . ']';
                $firstMatches = reset($parsedVariables);
                $keyInOptions = $firstMatches[$key];
                $options = str_replace($keyInOptions, $valueInData, $options);
            }
        }
        $jsonOptions = json_decode($options);
        return $jsonOptions;
    }

    public function render()
    {
        $block = $this->block;
        $queriedData = $this->queriedData;
        if (!$block->chart_json) return Blade::render("<x-feedback.alert type='warning' message='Please configure the chart options.'></x-feedback.alert>");
        $jsonOptions = $this->changeToJsonOptions($block->chart_json, $queriedData);
        
        $key = hash('sha256', $block->name);
        // dump($key, $jsonOptions);
        return Blade::render('<x-reports2.report-chart ' . 'key="{{$key}}" :jsonOptions="$jsonOptions" />', [
            'key' => $key,
            'jsonOptions' => $jsonOptions,
        ]);
    }
}

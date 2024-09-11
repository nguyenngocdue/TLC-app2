<?php

namespace App\View\Components\Reports2;

use App\View\Components\Reports2\Charts\TraitTransformationData;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ReportBlockChart extends Component
{
    use TraitReportDataAndColumn;

    public function __construct(
        private $block = null,
        private $queriedData = null,
        private $headerCols = [],
        private $fieldTransformation = [],
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
        $options = str_replace('{{series}}', $seriesJson, $options);
        return $options;
    }

    private function changeToJsonOptions(string $options, $queriedData)
    {
        $fieldTransformation = $this->fieldTransformation;
        if($fieldTransformation) {
            $options = $this->changeToJsonOptionsByTransformation($options, $queriedData, $fieldTransformation);
        } else {
            preg_match_all('/(?<!\\\)\{%\\s*([^}]*)\s*\%}/', $options, $matches);
            foreach (last($matches) as $key => $value) {
                $keyInDta = trim(str_replace('$', '', $value));
                $valueInData = $queriedData->pluck($keyInDta)->toArray();
                $valueInData = '['. implode(',' , array_map(fn($item) => "\"{$item}\"",$valueInData)) . ']';
                $firstMatches = reset($matches);
                $keyInOptions = $firstMatches[$key];
                $options = str_replace($keyInOptions, $valueInData, $options);
            }
        }
        $jsonOptions = json_decode($options);
        // dump($options, $jsonOptions);
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

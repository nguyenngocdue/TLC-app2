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
        private $headerCols = []
    ) {}

    private function changeToJsonOptions(string $options, $queriedData)
    {
        preg_match_all('/(?<!\\\)\{\{\s*([^}]*)\s*\}\}/', $options, $matches);
        foreach (last($matches) as $key => $value) {
            $keyInDta = trim(str_replace('$', '', $value));
            $valueInData = $queriedData->pluck($keyInDta)->toArray();
            $valueInData = '['. implode(',' ,$valueInData) . ']';

            $firstMatches = reset($matches);
            $keyInOptions = $firstMatches[$key];
            $options = str_replace($keyInOptions, $valueInData, $options);
        }
        $jsonOptions = json_decode($options);
        // dump($options, $jsonOptions);
        return $jsonOptions;
    }

    public function render()
    {
        $block = $this->block;
        $queriedData = $this->queriedData;
        $jsonOptions = $this->changeToJsonOptions($block->chart_json, $queriedData);
        
        $key = hash('sha256', $block->name);
        // dump($key, $jsonOptions);
        return Blade::render('<x-reports2.report-chart ' . 'key="{{$key}}" :jsonOptions="$jsonOptions" />', [
            'key' => $key,
            'jsonOptions' => $jsonOptions,
        ]);
    }
}

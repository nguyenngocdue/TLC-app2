<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;
use Illuminate\View\Component;

class ReportManyBlockCharts extends Component
{
    use TraitReportChartOption;

    public function __construct(
        private $block = null,
        private $reportId  = null,
        private $queriedData = null,
        private $transformedFields = [],
        private $currentParams = [],

    ) {}

    private function updateJsonOptions($jsonOptions, $xAxisValue, $seriesValue, $setting, $isRow = false) {
        $arrayOptions = json_decode(json_encode($jsonOptions), true);
        $arrayOptions["xAxis"]["data"] = $isRow ? [$xAxisValue] : $xAxisValue;
        $arrayOptions["series"][0]["data"] = $isRow ? [$seriesValue] : $seriesValue;
        $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
        return json_decode(json_encode($arrayOptions));
    }
    
    private function updateJsonOptionsRow($jsonOptions, $xAxisValue, $seriesValue, $setting) {
        return $this->updateJsonOptions($jsonOptions, $xAxisValue, $seriesValue, $setting, true);
    }
    
    private function updateJsonOptionsCol($jsonOptions, $xAxisValue, $seriesValue, $setting) {
        return $this->updateJsonOptions($jsonOptions, $xAxisValue, $seriesValue, $setting, false);
    }
    

    public function render()
    {
        $block = $this->block;
        $optionStr =  $block->chart_json;
        $jsonOptions = $this->changeToJsonOptions($optionStr, $this->queriedData);

        $queriedData = $this->queriedData;
        $chartOptions = [];

        if (isset($jsonOptions->multipleChart)) {
            $mulChartConfig = $jsonOptions->multipleChart;
            $descriptions = $mulChartConfig->descriptions;
            $direction = $mulChartConfig->direction;
            $settings = $mulChartConfig->settings;
            $xAxisData = $mulChartConfig->xAxisData;
            switch ($direction) {
                case 'row':
                        foreach ($queriedData as $key => $value) {
                            $opts = [];
                            foreach ($settings as $setting) {
                                $seriesValue = $value->{$setting->dataIndex};
                                $xAxisValue = $xAxisData[$key];
                                $newOptions = $this->updateJsonOptionsRow($jsonOptions, $xAxisValue,$seriesValue, $setting);
                                $opts[]= $newOptions;

                            }
                            $chartOptions[$key]["descriptions"]["text"]  = Report::makeTitle($descriptions?->title) .': '.$value->{$descriptions->dataIndex};
                            $chartOptions[$key]["option_chart"]  = $opts;
                        }
                    break;
                case 'column':
                        foreach ($settings as $setting) {
                            $seriesValue = $queriedData->pluck($setting->dataIndex);
                            $newOptions = $this->updateJsonOptionsCol($jsonOptions, $xAxisData,$seriesValue, $setting);
                            
                            $chartOptions[]= $newOptions;
                        }
                    break;
                default:
                    // define it in the future
            }
        }
        $divClass = ($d = $block->div_class) ? $d : ' w-full h-full p-4 border border-gray-200';
        return view(
            'components.reports2.report-many-block-charts',
            [
                'jsonOptions' => $jsonOptions,
                'divClass' => $divClass,
                'reportId' => $this->reportId,
                'chartOptions' => $chartOptions,
            ]
        );
    }
}

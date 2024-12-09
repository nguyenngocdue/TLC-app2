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


    private function updateJsonOptionsRowCell($jsonOptions, $xAxisValue, $seriesValue, $setting)
    {
        $arrayOptions = json_decode(json_encode($jsonOptions), true);
        $arrayOptions["xAxis"]["data"] =  [$xAxisValue];
        $arrayOptions["series"][0]["data"] = [$seriesValue];
        $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
        $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
        return json_decode(json_encode($arrayOptions));
    }

    private function updateJsonOptionsCol($jsonOptions, $xAxisValue, $seriesValue, $setting)
    {
        $arrayOptions = json_decode(json_encode($jsonOptions), true);
        $arrayOptions["xAxis"]["data"] =  $xAxisValue;
        $arrayOptions["series"][0]["data"] = $seriesValue;
        $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
        $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
        return json_decode(json_encode($arrayOptions));
    }

    private function updateJsonOptionsRow($jsonOptions, $xAxisValue, $seriesValue, $setting)
    {
        $arrayOptions = json_decode(json_encode($jsonOptions), true);
        $arrayOptions["xAxis"]["data"] =  $xAxisValue;
        $arrayOptions["series"][0]["data"] = $seriesValue;
        $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
        $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
        return json_decode(json_encode($arrayOptions));
    }


    private function generateRowCharts($queriedData, $mulChartConfig, $jsonOptions, $settings, $xAxisData)
    {
        $chartOptions = [];
        foreach ($queriedData as $key => $value) {
            $opts = [];
            $seriesValues = [];
            $xAxisValues = [];
            foreach ($settings as $setting) {
                $seriesValues[] = $value->{$setting->dataIndex};
                $xAxisValues[] = $setting->title ?? $setting->dataIndex;
            }
            $opts[$key] = $this->updateJsonOptionsRow($jsonOptions, $xAxisValues, $seriesValues, $settings[0]);
            $chartOptions[$key]["descriptions"]["text"] = Report::makeTitle($mulChartConfig->descriptions?->title) . ': ' . $value->{$mulChartConfig->descriptions->dataIndex};
            $chartOptions[$key]["chart_option"] = $opts;
        }
        return $chartOptions;
    }


    private function generateRowCellCharts($queriedData, $mulChartConfig, $jsonOptions, $settings, $xAxisData)
    {
        $chartOptions = [];
        foreach ($queriedData as $key => $value) {
            $opts = [];
            foreach ($settings as $setting) {
                $seriesValue = $value->{$setting->dataIndex};
                $xAxisValue = $xAxisData[$key];
                $opts[] = $this->updateJsonOptionsRowCell($jsonOptions, $xAxisValue, $seriesValue, $setting);
            }
            $chartOptions[$key]["descriptions"]["text"] = Report::makeTitle($mulChartConfig->descriptions?->title) . ': ' . $value->{$mulChartConfig->descriptions->dataIndex};
            $chartOptions[$key]["chart_option"] = $opts;
        }
        return $chartOptions;
    }



    private function generateColumnCharts($queriedData, $mulChartConfig, $jsonOptions, $settings, $xAxisData)
    {
        $chartOptions = [];
        $opts = [];
        foreach ($settings as $key => $setting) {
            $seriesValue = $queriedData->pluck($setting->dataIndex);
            $opts[] = $this->updateJsonOptionsCol($jsonOptions, $xAxisData, $seriesValue, $setting);
        }
        $chartOptions[$key]["chart_option"] = $opts;
        $chartOptions[$key]["descriptions"]["text"] = Report::makeTitle($mulChartConfig->descriptions?->title);
        return $chartOptions;
    }

    private function generateChartOptions($jsonOptions, $queriedData)
    {
        $chartOptions = [];
        if (!isset($jsonOptions->multipleChart)) {
            return $chartOptions; // Return empty if no multipleChart configuration
        }
        $mulChartConfig = $jsonOptions->multipleChart;
        $settings = $mulChartConfig->settings;
        $xAxisData = $mulChartConfig->xAxisData;
        switch ($mulChartConfig->direction) {
            case 'row':
                $chartOptions = $this->generateRowCharts($queriedData, $mulChartConfig, $jsonOptions, $settings, $xAxisData);
                break;
            case 'row_cell':
                $chartOptions = $this->generateRowCellCharts($queriedData, $mulChartConfig, $jsonOptions, $settings, $xAxisData);
                break;
            case 'column':
                $chartOptions = $this->generateColumnCharts($queriedData, $mulChartConfig, $jsonOptions, $settings, $xAxisData);
                break;
            default:
                // Placeholder for future directions
        }
        return $chartOptions;
    }



    public function render()
    {
        $block = $this->block;
        $optionStr =  $block->chart_json;
        $jsonOptions = $this->changeToJsonOptions($optionStr, $this->queriedData);
        $queriedData = $this->queriedData;

        $chartOptions = $this->generateChartOptions($jsonOptions, $queriedData);
        
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

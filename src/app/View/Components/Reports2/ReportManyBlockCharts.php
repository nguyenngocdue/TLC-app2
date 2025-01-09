<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use InvalidArgumentException;

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

    // private function updateJsonOptionsRowCell($jsonOptions, $xAxisValue, $seriesValue, $setting)
    // {
    //     $arrayOptions = json_decode(json_encode($jsonOptions), true);
    //     $arrayOptions["xAxis"]["data"] =  [$xAxisValue];
    //     $arrayOptions["series"][0]["data"] = [$seriesValue];
    //     $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
    //     $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
    //     return json_decode(json_encode($arrayOptions));
    // }

    // private function updateJsonOptionsCol($jsonOptions, $xAxisValue, $seriesValue, $setting)
    // {
    //     $arrayOptions = json_decode(json_encode($jsonOptions), true);
    //     $arrayOptions["xAxis"]["data"] =  $xAxisValue;
    //     $arrayOptions["series"][0]["data"] = $seriesValue;
    //     $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
    //     $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
    //     return json_decode(json_encode($arrayOptions));
    // }

    // private function updateJsonOptionsRow($jsonOptions, $xAxisValue, $seriesValue, $setting)
    // {
    //     $arrayOptions = json_decode(json_encode($jsonOptions), true);
    //     $arrayOptions["xAxis"]["data"] =  $xAxisValue;
    //     $arrayOptions["series"][0]["data"] = $seriesValue;
    //     $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
    //     $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
    //     return json_decode(json_encode($arrayOptions));
    // }

    private function updateJsonOptions($jsonOptions, $xAxisValue, $seriesValue, $settings, $direction)
    {
        $arrayOptions = json_decode(json_encode($jsonOptions), true);

        switch ($direction) {
            case 'row_cell':
                $arrayOptions["xAxis"]["data"] = [$xAxisValue];
                $arrayOptions["series"][0]["data"] = [$seriesValue];
                break;
            case 'column':
            case 'row':
                $arrayOptions["xAxis"]["data"] = $xAxisValue;
                $arrayOptions["series"][0]["data"] = $seriesValue;
                break;
            default:
                // Handle invalid direction or add default behavior
                throw new InvalidArgumentException("Unsupported direction: $direction");
        }
        // $arrayOptions["series"][0]["name"] = $setting->title ?? $setting->dataIndex;
        // $arrayOptions["title"]["text"] = $setting->title ?? $setting->dataIndex;
        $arrayOptions["series"][0]["name"] = "";
        // $arrayOptions["title"]["text"] = "";


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
            $opts[$key] = $this->updateJsonOptions($jsonOptions, $xAxisValues, $seriesValues, $settings[0], 'row');
            $chartOptions[$key]["descriptions"]["text"] = Report::makeTitle($mulChartConfig->descriptions?->title) . ': ' . $value->{$mulChartConfig->descriptions->dataIndex};
            $chartOptions[$key]["descriptions"]["cssClass"] = $mulChartConfig->descriptions->cssClass ?? 'text-lg text-lg-vw leading-tight text-gray-800 font-bold dark:bg-gray-700 dark:text-gray-400 p-4 mb-2';
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
                $opts[] = $this->updateJsonOptions($jsonOptions, $xAxisValue, $seriesValue, $setting, 'row_cell');
            }
            $chartOptions[$key]["descriptions"]["text"] = Report::makeTitle($mulChartConfig->descriptions?->title) . ': ' . $value->{$mulChartConfig->descriptions->dataIndex};
            $chartOptions[$key]["descriptions"]["cssClass"] = $mulChartConfig->descriptions->cssClass ?? 'text-lg text-lg-vw leading-tight text-gray-800 font-bold dark:bg-gray-700 dark:text-gray-400 p-4 mb-2';
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
            $opts[] = $this->updateJsonOptions($jsonOptions, $xAxisData, $seriesValue, $setting, 'column');
        }
        $chartOptions[$key]["chart_option"] = $opts;
        $chartOptions[$key]["descriptions"]["text"] = Report::makeTitle($mulChartConfig->descriptions?->title);
        $chartOptions[$key]["descriptions"]["cssClass"] = $mulChartConfig->descriptions->cssClass ?? 'text-lg text-lg-vw leading-tight text-gray-800 font-bold dark:bg-gray-700 dark:text-gray-400 p-4 mb-2';
        return $chartOptions;
    }

    private function addChartTitle($chartOptions, $chartTitleStr) {
        $trimmedText = trim($chartTitleStr, "[]");
        $chartTitles = $chartTitleStr ?  explode(',', $trimmedText) : '';
        if (!empty($chartTitles)) {
            foreach ($chartOptions as &$values){
                $configs = $values["chart_option"];
                foreach ($configs as $k2 => &$val) {
                    if (isset($val->title->text)) {
                        $val->title->text =  isset($chartTitles[$k2]) ?  trim($chartTitles[$k2]) : '';
                    }
                }
                $values["chart_option"] = $configs;
            }
        }
        return  $chartOptions;
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
        $chartTitleStr = $jsonOptions->title->text;
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
        // switch ($mulChartConfig->direction) {
        //     case 'row':
        //     case 'row_cell':
        //     case 'column':
        //         $chartOptions = $this->updateJsonOptions(
        //             $jsonOptions,
        //             $xAxisData,
        //             $queriedData,
        //             $settings,
        //             $mulChartConfig->direction
        //         );
        //         break;
        //     default:
        //         throw new InvalidArgumentException("Unsupported direction: $mulChartConfig->direction");
        // }

        $chartOptions = $this->addChartTitle($chartOptions, $chartTitleStr);
        return $chartOptions;
    }



    public function render()
    {
        $queriedData = $this->queriedData;

        $block = $this->block;
        $optionStr =  $block->chart_json;
        $jsonOptions = $this->changeToJsonOptions($optionStr, $this->queriedData);

        if ($queriedData->isEmpty()) {
            $title = $jsonOptions?->multipleChart?->descriptions?->title . ': (Empty Data)' ?? "Render Title of Description";
            return Blade::render("
            <x-renderer.heading class='text-lg text-lg-vw leading-tight text-gray-800 font-bold bg-gray-50 dark:bg-gray-700 dark:text-gray-400 p-4 mb-2' level=4>{$title}</x-renderer.heading>
            <div class='max-w-md mx-auto bg-white shadow-lg rounded-lg p-6 text-center'>
                <!-- Icon -->
                <div class='text-red-500 mb-4'>
                    <svg class='w-16 h-16 mx-auto' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                        <path stroke-linecap='round' stroke-linejoin='round' d='M9.172 9.172a4 4 0 015.656 0l5.657 5.657a4 4 0 01-5.656 5.656L9.172 14.83a4 4 0 010-5.656zm-1.415 1.415a6 6 0 118.486 8.486'></path>
                    </svg>
                </div>
                <!-- Text -->
                <h1 class='text-2xl font-semibold text-gray-800 mb-2'>No Data Available</h1>
                <p class='text-gray-600'>The data source is empty. Please check again later.</p>
            </div>");
        }

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

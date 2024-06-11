<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;
use App\Models\Term;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProdSequences extends ViewAllTypeMatrixParent
{
    // use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "prod_routing_link_id";
    protected $yAxis = Prod_order::class;
    protected $dataIndexY = "prod_order_id";
    // protected $rotate45Width = 400;
    protected $tableTrueWidth = true;
    protected $headerTop = 20;
    protected $groupBy = null;
    protected $mode = 'detail';

    protected $showAdvancedDays = !true;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, $this->subProject, $this->prodRouting, $this->prodRoutingLink, $this->prodDiscipline] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : null;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : null;
        $this->prodRoutingLink = $this->prodRoutingLink ? $this->prodRoutingLink : [];
        // $this->prodDiscipline = $this->prodDiscipline ? $this->prodDiscipline : 2;
        // dump($this->project, $this->subProject, $this->prodRouting);
        $this->cacheUnit();
    }

    private $unit;
    private function cacheUnit()
    {
        $result = [];
        $terms = Arr::groupBy(Term::all()->toArray(), 'id');
        foreach ($terms as $key => $subArr) {
            $result[$key]   = array_pop($subArr);
        }
        // dump($result);
        $this->unit = $result;
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        $prodRoutingLink = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_link_id'] ?? null;
        $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
        $result = [$project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline];
        // Log::info($result);
        return $result;
    }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            ->with('getRoomType')
            ->orderBy('name')
            ->get();
        return $yAxis;
    }

    protected function getXAxisExtraColumns()
    {
        return ["start_date", "end_date", "man_power", "uom", "total_uom", "total_mins", "min_per_uom"];
    }

    protected function getXAxisPrimaryColumns()
    {
        $data = Prod_routing::find($this->prodRouting)
            ->getProdRoutingLinks();
        if ($this->prodDiscipline) {
            $data = $data->where('prod_discipline_id', $this->prodDiscipline);
        } else { //In case if empty is provided, do not return PPR-MEPF
            $toHide = config('prod_discipline.to_hide');
            $data = $data->whereNotIn('prod_discipline_id', $toHide);
        }
        if ($this->prodRoutingLink) $data = $data->whereIn('prod_routing_link_id', $this->prodRoutingLink);
        $data = $data->orderBy('order_no')->get();
        return $data;
    }

    protected function getXAxis()
    {
        $result = [];
        if (is_null($this->subProject)) {
            echo Blade::render("<x-feedback.alert type='error' message='You must specify Sub-Project.'></x-feedback.alert>");
            return [];
        }
        if (is_null($this->prodRouting)) {
            echo Blade::render("<x-feedback.alert type='error' message='You must specify Prod Routing.'></x-feedback.alert>");
            return [];
        }
        $data = $this->getXAxisPrimaryColumns();
        // dump($data);
        $extraColumns = $this->getXAxisExtraColumns();
        foreach ($data as $line) {
            $description = ($line->description ? "<br/>" . $line->description : "");
            $standard_uom = ($line->standard_uom_id ? "<br/>(" . ($this->unit[$line->standard_uom_id]['name'] ?? "") . ")" : "");
            $result[] = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name . $description . $standard_uom,
                'align' => 'center',
                'width' => 40,
                'prod_discipline_id' => $line->prod_discipline_id,
                "colspan" => 1 + sizeof($extraColumns),

                "target_man_minutes" => $line->pivot->target_man_hours * 60,
                "target_man_power" => $line->pivot->target_man_power,
                "target_min_uom" => $line->pivot->target_min_uom,

            ];
            foreach ($extraColumns as $column) {
                $item = [
                    'dataIndex' => $line->id . "_" . $column,
                    'columnIndex' => $column,
                    'align' => 'center',
                    'width' => 40,
                    'isExtra' => true,
                ];
                // if ($column == 'min_per_uom') $item['title'] = "Min/UoM";
                $result[] = $item;
            }
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $lines = Prod_sequence::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting);
        if ($this->prodDiscipline) $lines = $lines->where('prod_discipline_id', $this->prodDiscipline);
        if ($this->prodRoutingLink) $lines = $lines->whereIn('prod_routing_link_id', $this->prodRoutingLink);
        return $lines->get();
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            'prod_routing_link_id' => $this->prodRoutingLink,
            'prod_discipline_id' => $this->prodDiscipline,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        // $params['project_id'] =  $this->project;
        $params['sub_project_id'] =  $this->subProject;
        $params['prod_routing_id'] =  $this->prodRouting;

        $params['prod_discipline_id'] =  $x['prod_discipline_id'];
        return $params;
    }

    protected function getMetaColumns()
    {
        $columns = [
            ['dataIndex' => 'production_name',  'width' => 300, 'fixed' => 'left',],
            ['dataIndex' => 'quantity', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],
            ['dataIndex' => 'prod_sequence_progress',  'title' => "Progress (%)", 'width' => 300, 'fixed' => 'left',],
            ['dataIndex' => 'status',  'align' => 'center', 'width' => 50, 'fixed' => 'left-no-bg', "title" => "Summary", 'colspan' => 4],
            ['dataIndex' => 'room_type',  'align' => 'center', 'width' => 50, 'fixed' => 'left',],
            ['dataIndex' => 'started_at', 'align' => 'right', 'width' => 150, 'fixed' => 'left',],
            ['dataIndex' => 'finished_at', 'align' => 'right', 'width' => 150, 'fixed' => 'left',],

            ['dataIndex' => 'total_calendar_days', 'align' => 'right', 'width' => 50, 'fixed' => 'left', "title" => "Total", 'colspan' => 3],

            ['dataIndex' => 'net_working_days', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],
            ['dataIndex' => 'total_man_hours', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],

        ];

        if ($this->showAdvancedDays) {
            $advColumns =  [
                ['dataIndex' => 'no_of_sundays', 'align' => 'right', 'width' => 50, 'fixed' => 'left', 'title' => "Advanced Metrics", 'colspan' => 5],
                ['dataIndex' => 'no_of_ph_days', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],
                ['dataIndex' => 'total_days_no_sun_no_ph', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],
                ['dataIndex' => 'total_days_have_ts', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],
                ['dataIndex' => 'total_discrepancy_days', 'align' => 'right', 'width' => 50, 'fixed' => 'left',],
            ];
            $columns = [...$columns, ...$advColumns,];
        }

        return $columns;
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        $started_at = DateTimeConcern::convertForLoading("picker_datetime", $y->started_at);
        $finished_at = DateTimeConcern::convertForLoading("picker_datetime", $y->finished_at);
        $finished_at = substr($finished_at, 0, 10);

        $finished_at = (in_array($y->status, ['finished'])) ? $finished_at : "<span class='text-gray-300'>$finished_at</span>";
        $status_object = $this->makeStatus($y, false);
        $status_object->cell_href = route("prod_orders" . ".edit", $y->id);
        $result = [
            'prod_sequence_progress' => (object)[
                'cell_div_class' => 'p-2 text-right',
                'value' => ($v = $y->prod_sequence_progress) ? number_format($v, 2) : ""
            ],
            'production_name' => (object)[
                'cell_div_class' => 'p-2 whitespace-nowrap',
                'value' => $y->production_name
            ],
            'quantity' => ($v = $y->quantity) ? $v : "",
            'status' => $status_object,
            'room_type' => (object)[
                'cell_div_class' => 'p-2 whitespace-nowrap',
                'value' => ($y->getRoomType) ? $y->getRoomType->name : ""
            ],
            'started_at' => substr($started_at, 0, 10),
            'finished_at' => $finished_at,
            // 'finished_at' => ($y->status === 'finished') ? substr($finished_at, 0, 10) : "",
            //<<TODO: use $y->total_calendar_days 
            // 'total_calendar_days' => $y->finished_at ? 1 + Carbon::parse($y->finished_at)->diffInDays($y->started_at) : "",
            'total_calendar_days' => $y->finished_at ? $y->total_calendar_days : "",

            'net_working_days' => number_format($y->total_hours / 8, 2),
            'total_man_hours' => number_format($y->total_man_hours, 2),
        ];

        $advColumns = [
            'no_of_sundays' => $y->no_of_sundays,
            'no_of_ph_days' => $y->no_of_ph_days,
            'total_days_no_sun_no_ph' => $y->total_days_no_sun_no_ph,
            'total_days_have_ts' => $y->total_days_have_ts,
            'total_discrepancy_days' => $y->total_discrepancy_days,
        ];

        if ($this->showAdvancedDays) {
            $result = $result + $advColumns;
        }

        // dump($result);

        return $result;
    }

    function cellRenderer($cell, $dataIndex, $x, $y, $forExcel = false, $matrixKey = null)
    {
        if (in_array($dataIndex, ['status', 'detail'])) return parent::cellRenderer($cell, $dataIndex, $x, $y, $forExcel);
        if ($dataIndex === 'checkbox_print') return parent::cellRenderer($cell, $dataIndex, $x, $y, $forExcel);
        $doc = $cell[0];
        if ($doc->status === 'not_applicable') {
            return (object)[
                "value" => "",
                "cell_class" => "bg-gray-300",
            ];
        }
        switch ($dataIndex) {
            case "total_uom":
                return number_format(round($doc->{$dataIndex}, 2), 2);
            case "start_date":
                return ($date = $doc->{$dataIndex}) ? date(Constant::FORMAT_DATE_ASIAN, strtotime($date)) : "";
            case "end_date":
                $text = ($date = $doc->{$dataIndex}) ? date(Constant::FORMAT_DATE_ASIAN, strtotime($date)) : "";
                if (in_array($doc->status, ['finished'])) {
                    return $text;
                }
                return "<span class='text-gray-300'>$text</span>";
            case "man_power":
                return round($doc->worker_number, 2);
            case "total_mins":
                return number_format(round($doc->total_hours * 60));
            case "min_per_uom":
                if ($doc->total_uom > 0) {
                    $value = round($doc->total_hours * 60 / $doc->total_uom, 2);
                    $target = $x['target_min_uom'];
                    if (!$target || !$value) return $value;
                    if (!in_array($doc->status, ['finished'])) return "<span class='text-gray-300'>" . $value . "</span>";

                    $color = $target >= $value ? "green" : "red";
                    $percent = round(100 * ($target - $value) / $target);
                    return (object)[
                        "value" => number_format($value, 2),
                        "cell_class" => "text-$color-700 bg-$color-300 font-bold",
                        "cell_title" => "Target: " . $target . " - Variance: " . round($target - $value, 2) . " ($percent%)",
                    ];
                } else {
                    return '<i class="fa-solid fa-infinity" title="DIV 0"></i>';
                }
            case "uom":
                return (object) [
                    'cell_div_class' => 'p-2 whitespace-nowrap',
                    "value" =>  $this->unit[$doc->uom_id]['name'] ?? "(unknown unit)"
                ];
            default:
                // if (isset($doc->{$dataIndex})) return $doc->{$dataIndex};
                return "852. Not found " . $dataIndex;
        }
    }
    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $line) {
            $result[$line['dataIndex']] =  Str::headline($line['columnIndex']);
            if ($line['columnIndex'] == 'total_uom') $result[$line['dataIndex']] = "Total UoM";
            if ($line['columnIndex'] == 'min_per_uom') $result[$line['dataIndex']] = "min/UoM";
            if ($line['columnIndex'] == 'uom') $result[$line['dataIndex']] = "UoM";
            if ($line['columnIndex'] == 'total_mins') $result[$line['dataIndex']] = "min";
            if ($line['columnIndex'] == 'man_power') $result[$line['dataIndex']] = "m'p";
            if ($line['columnIndex'] == 'start_date') $result[$line['dataIndex']] = "Start";
            if ($line['columnIndex'] == 'end_date') $result[$line['dataIndex']] = "Finish";
        }

        //For Meta columns
        $result['status'] = "Status";
        $result['room_type'] = "Product Type";
        $result['started_at'] = "Start";
        $result['finished_at'] = "Finish";
        $result['total_calendar_days'] = "Total Calendar Days";
        $result['net_working_days'] = "Net Working Days";
        $result['total_man_hours'] = "Total ManHours";

        if ($this->showAdvancedDays) {
            $result['no_of_sundays'] = "No. Sundays";
            $result['no_of_ph_days'] = "No. PH Days";
            $result['total_days_no_sun_no_ph'] = "Total Working Days";
            $result['total_days_have_ts'] = "Total Days Have TS";
            $result['total_discrepancy_days'] = "Total Discrepancy Days";
        }

        foreach ($result as &$row) {
            $row = "<div class='p-1 text-center'>" . $row . "</div>";
        }
        return $result;
    }
}

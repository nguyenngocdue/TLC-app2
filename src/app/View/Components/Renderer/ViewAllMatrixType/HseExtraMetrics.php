<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Hr_timesheet_worker;
use App\Models\Hse_extra_metric;
use App\Models\User;
use App\Models\User_team_tsht;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HseExtraMetrics extends ViewAllTypeMatrixParent
{
    protected $viewportDate = null;
    protected $viewportMode = null;

    protected $dataIndexX = "metric_month";
    protected $dataIndexY = "workplace_id";

    protected $yAxis = Workplace::class;
    // protected $xAxis = Date::class;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->viewportDate, $this->viewportMode] = $this->getUserSettings();
        $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        if (!$this->viewportMode) $this->viewportMode = 'week';
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $viewportDate = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_date'] ?? null;
        $viewportMode = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_mode'] ?? null;
        return [$viewportDate, $viewportMode];
    }

    protected function getYAxis()
    {
        $yAxis = $this->yAxis::orderBy('name')->get();
        return $yAxis;
    }

    protected function getXAxis()
    {
        $xAxis = [];
        for ($i = 01; $i <= 12; $i++) {
            $xAxis[] = sprintf("2023-%02d-01", $i);
        }
        // dump($xAxis);

        $xAxis = array_map(fn ($c) => [
            'dataIndex' => $c,
            'title' => date(Constant::FORMAT_MONTH, strtotime($c)),
            'width' => 10,
            'align' => 'center',
        ], $xAxis);
        return $xAxis;
    }

    protected function getMatrixDataSource($xAxis)
    {
        // dump($xAxis);
        // $firstDay = $xAxis[0]['dataIndex'];
        // $lastDay =  $xAxis[sizeof($xAxis) - 1]['dataIndex'];
        $lines = Hse_extra_metric::whereYear('metric_month', 2023)->get();
        // dump($lines);
        return $lines;
    }

    protected function getFilterDataSource()
    {
        $minus1year = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 year", $this->viewportDate));
        $minus1month = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 month", $this->viewportDate));
        $minus1week = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 week", $this->viewportDate));
        $today = date(Constant::FORMAT_DATE_MYSQL);
        $plus1week = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 week", $this->viewportDate));
        $plus1month = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 month", $this->viewportDate));
        $plus1year = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 year", $this->viewportDate));

        return [
            '-1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1year",
            '-1month' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1month",
            '-1week' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1week",
            'today' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$today",
            '+1week' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1week",
            '+1month' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1month",
            '+1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1year",

            'weekView' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_mode=week",
            'monthView' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_mode=month",
        ];
    }

    protected function getViewportParams()
    {
        return ['mode' => $this->viewportMode];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        return $params;
    }

    // protected function getMetaColumns()
    // {
    //     return [
    //         ['dataIndex' => 'meta01', 'title' => 'Name', 'width' => 150,],
    //         ['dataIndex' => 'count', 'align' => 'center', 'width' => 50],
    //     ];
    // }

    // function getMetaObjects($y)
    // {
    //     return [
    //         'meta01' => (object) [
    //             'value' => User::findFromCache($y->def_assignee)->name,
    //             'cell_title' => $y->def_assignee,
    //         ],
    //         'count' => count($y->getTshtMembers()),
    //     ];
    // }
}

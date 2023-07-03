<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Models\Hr_timesheet_worker;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ViewAllTypeMatrixTypeDateMode extends ViewAllTypeMatrixParent
{
    protected $viewportDate = null;
    protected $viewportMode = null;

    protected $dataIndexX = "ts_date";
    protected $dataIndexY = "team_id";
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

    private function getBeginEndFromViewMode($date)
    {
        switch ($this->viewportMode) {
            case 'month':
                [$begin, $end] = DateTimeConcern::getMonthBeginAndEndDate0($date);
                $begin = Carbon::createFromDate($begin)->diffInDays($date);
                $end = Carbon::createFromDate($end)->diffInDays($date);
                return [-$begin, $end + 1];
            case 'week':
            default:
                return [-7, 1];
        }
    }

    private function getColumnTitleFromViewMode($c)
    {
        switch ($this->viewportMode) {
            case 'month':
                return date('d', strtotime($c)) . "<br/>" . date('m', strtotime($c)) . "<br/>" . date('y', strtotime($c));
            case 'week':
            default:
                return date(Constant::FORMAT_DATE_ASIAN, strtotime($c)) . "<br>" . date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($c));
        }
    }

    protected function getYAxis()
    {
        $yAxis = $this->yAxis::orderBy('name')->get();
        return $yAxis;
    }

    protected function getXAxis()
    {
        $xAxis = [];
        $date0 = date(Constant::FORMAT_DATE_MYSQL, $this->viewportDate); //today date
        [$begin, $end] = $this->getBeginEndFromViewMode($date0);
        for ($i = $begin; $i < $end; $i++) {
            $date = date(Constant::FORMAT_DATE_MYSQL, strtotime("+$i day", strtotime($date0)));
            $xAxis[] = date(Constant::FORMAT_DATE_MYSQL, strtotime($date));
        }
        // dump($xAxis);

        $xAxis = array_map(fn ($c) => [
            'dataIndex' => $c,
            'title' => $this->getColumnTitleFromViewMode($c),
            'column_class' => ("Sun" == date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($c))) ? "bg-gray-300" : (($c == date(Constant::FORMAT_DATE_MYSQL)) ? "bg-red-200 animate-pulse animate-bounce1" : ""),
            'width' => 10,
            'align' => 'center',
        ], $xAxis);
        return $xAxis;
    }

    protected function getMatrixDataSource($xAxis)
    {
        // dump($xAxis);
        $firstDay = $xAxis[0]['dataIndex'];
        $lastDay =  $xAxis[sizeof($xAxis) - 1]['dataIndex'];
        $lines = Hr_timesheet_worker::whereBetween('ts_date', [$firstDay, $lastDay])->get();
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
            '-1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$minus1year",
            '-1month' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$minus1month",
            '-1week' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$minus1week",
            'today' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$today",
            '+1week' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$plus1week",
            '+1month' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$plus1month",
            '+1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$plus1year",

            'weekView' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportMode=week",
            'monthView' => "?action=updateViewAllMatrix&_entity={$this->type}&viewportMode=month",
        ];
    }

    protected function getViewportParams()
    {
        return ['mode' => $this->viewportMode];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['assignee_1'] =  $y->def_assignee;
        return $params;
    }
}

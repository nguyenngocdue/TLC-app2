<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Hr_timesheet_worker;
use App\Models\User;
use App\Models\User_team_tsht;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterWeekMonth;
use Illuminate\Support\Str;

class HrTimesheetWorkers extends ViewAllTypeMatrixParent
{
    use TraitXAxisDate;
    use TraitFilterWeekMonth;

    protected $viewportDate = null;
    protected $viewportMode = null;

    protected $dataIndexX = "ts_date";
    protected $dataIndexY = "team_id";

    protected $yAxis = User_team_tsht::class;
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

    protected function getMatrixDataSource($xAxis)
    {
        // dd($xAxis);
        $lines = collect();
        if (sizeof($xAxis)) {
            $firstDay = $xAxis[0]['dataIndex'];
            $lastDay =  $xAxis[sizeof($xAxis) - 1]['dataIndex'];
            $lines = Hr_timesheet_worker::whereBetween('ts_date', [$firstDay, $lastDay])->get();
        } else {
            dump("xAxis is empty.");
        }
        return $lines;
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

    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'meta01', 'title' => 'Name', 'width' => 150, "fixed" => 'left'],
            ['dataIndex' => 'count', 'align' => 'center', 'width' => 50, "fixed" => 'left'],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        return [
            'meta01' => (object) [
                'value' => User::findFromCache($y->def_assignee)->name,
                'cell_title' => $y->def_assignee,
            ],
            'count' => count($y->getTshtMembers()),
        ];
    }
}

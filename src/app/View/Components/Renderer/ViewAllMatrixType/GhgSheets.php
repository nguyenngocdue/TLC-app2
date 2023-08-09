<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Ghg_sheet;
use App\Models\Ghg_tmpl;
use App\Models\Hse_extra_metric;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterMonth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GhgSheets extends ViewAllTypeMatrixParent
{
    use TraitFilterMonth;

    protected $viewportDate = null;
    // protected $viewportMode = null;

    protected $dataIndexX = "ghg_month";
    protected $dataIndexY = "ghg_tmpl_id";

    protected $yAxis = Ghg_tmpl::class;
    protected $groupBy = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->viewportDate] = $this->getUserSettings();
        $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        // dump($this->viewportDate);
        // if (!$this->viewportMode) $this->viewportMode = 'week';
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $viewportDate = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_date'] ?? null;
        // $viewportMode = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_mode'] ?? null;
        return [$viewportDate];
    }

    protected function getYAxis()
    {
        $yAxis = $this->yAxis::orderBy('name')->get();
        return $yAxis;
    }

    protected function getXAxis()
    {
        $selectedYear = date('Y', $this->viewportDate);
        $xAxis = [];
        for ($i = 01; $i <= 12; $i++) {
            $xAxis[] = sprintf("$selectedYear-%02d-01", $i);
        }
        // dump($xAxis);

        $xAxis = array_map(fn ($c) => [
            'dataIndex' => substr($c, 0, 7),
            'title' => date(Constant::FORMAT_MONTH, strtotime($c)),
            'width' => 10,
            'align' => 'center',
        ], $xAxis);
        return $xAxis;
    }

    protected function getMatrixDataSource($xAxis)
    {
        $selectedYear = date('Y', $this->viewportDate);
        // dump($xAxis);
        // $firstDay = $xAxis[0]['dataIndex'];
        // $lastDay =  $xAxis[sizeof($xAxis) - 1]['dataIndex'];
        $lines = Ghg_sheet::query()
            ->whereYear('ghg_month', $selectedYear)
            ->select(["*", DB::raw(" substr(ghg_month,1,7) as ghg_month")])
            ->get();
        // dump($lines);
        return $lines;
    }

    protected function getViewportParams()
    {
        return [
            // 'mode' => $this->viewportMode
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['ghg_month'] .=  '-01';
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

    // function getMetaObjects($y, $dataSource, $xAxis)
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

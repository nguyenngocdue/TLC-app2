<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Hse_extra_metric;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterMonth;
use Illuminate\Support\Str;

class HseExtraMetrics extends ViewAllTypeMatrixParent
{
    use TraitFilterMonth;

    protected $viewportDate = null;
    // protected $viewportMode = null;

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
            'dataIndex' => $c,
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
        $lines = Hse_extra_metric::whereYear('metric_month', $selectedYear)->get();
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

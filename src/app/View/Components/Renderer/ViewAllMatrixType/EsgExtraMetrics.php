<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Esg_extra_metric;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterMonth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EsgExtraMetrics extends ViewAllTypeMatrixParent
{
    use TraitFilterMonth;
    use TraitXAxisMonthly;

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

    protected function getMatrixDataSource($xAxis)
    {
        $selectedYear = date('Y', $this->viewportDate);
        // dump($xAxis);
        // $firstDay = $xAxis[0]['dataIndex'];
        // $lastDay =  $xAxis[sizeof($xAxis) - 1]['dataIndex'];
        $lines = Esg_extra_metric::query()
            ->whereYear('metric_month', $selectedYear)
            ->select(["*", DB::raw(" substr(metric_month,1,7) as metric_month")])
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
        $params['metric_month'] .=  '-01';
        $params['status'] =  'new';
        return $params;
    }
}

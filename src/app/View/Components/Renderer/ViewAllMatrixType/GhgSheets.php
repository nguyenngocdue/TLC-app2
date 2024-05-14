<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Ghg_sheet;
use App\Models\Ghg_tmpl;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterMonth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GhgSheets extends ViewAllTypeMatrixParent
{
    use TraitFilterMonth;
    use TraitXAxisMonthly;

    protected $viewportDate = null;
    // protected $viewportMode = null;

    protected $dataIndexX = "ghg_month";
    protected $dataIndexY = "ghg_tmpl_id";

    protected $yAxis = Ghg_tmpl::class;
    protected $groupBy = null;
    protected $apiToCallWhenCreateNew = 'cloneTemplate';
    protected $attOfCellToRender = "total";
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

    protected function getRightMetaColumns()
    {
        return [
            ['dataIndex' => 'ytd', 'title' => 'YTD (KgCO2/Unit)', "align" => "right", 'width' => 100,],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        $line = $dataSource[$y->id] ?? [];
        $ytd = 0;
        foreach ($line as $month) {
            foreach ($month as $doc) {
                $ytd += $doc->total;
            }
        }
        return [
            'ytd' => (object) [
                'value' => number_format($ytd, 2),
                'cell_class' => "bg-text-400 text-right1",
            ],
        ];
    }
}

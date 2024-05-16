<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Esg_master_sheet;
use App\Models\Esg_tmpl;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterMonth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EsgMasterSheets extends ViewAllTypeMatrixParent
{
    use TraitXAxisMonthly;

    private $workplaceId;
    protected $viewportDate = null;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "esg_month";
    protected $yAxis = Esg_tmpl::class;
    protected $dataIndexY = "esg_tmpl_id";
    // protected $rotate45Width = 400;
    // protected $tableTrueWidth = true;
    protected $headerTop = 20;
    protected $groupBy = null;
    // protected $mode = 'status';
    protected $attOfCellToRender = "total";
    protected $cellAgg = "agg_sum";

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->workplaceId, $this->viewportDate] = $this->getUserSettings();
        $this->viewportDate .= '-01-01';
        $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        $this->allowCreation = !!$this->workplaceId;
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $workplaceId = $settings[$type][Constant::VIEW_ALL]['matrix']['workplace_id'] ?? null;
        $viewportDate = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_date'] ?? null;
        $result = [$workplaceId, $viewportDate];
        return $result;
    }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            ->with('getUnit')
            // ->where('sub_project_id', $this->subProject)
            // ->where('prod_routing_id', $this->prodRouting)
            // ->with('getRoomType')
            ->orderBy('name')
            ->get();
        return $yAxis;
    }

    public function getMatrixDataSource($xAxis)
    {
        $selectedYear = date('Y', $this->viewportDate);
        $lines = Esg_master_sheet::query()
            ->whereYear('esg_month', $selectedYear);
        // Log::info($this->workplaceId);
        if ($this->workplaceId) $lines = $lines->where('workplace_id', $this->workplaceId);
        $lines = $lines->select(["*", DB::raw(" substr(esg_month,1,7) as esg_month")])
            ->get();
        // dump($lines[0]);
        return $lines;
    }

    protected function getViewportParams()
    {
        return [
            'workplace_id' => $this->workplaceId,
            'viewport_date' => $this->viewportDate,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        $params['esg_month'] .=  '-01';
        $params['workplace_id'] = $this->workplaceId;
        return $params;
    }

    protected function getRightMetaColumns()
    {
        return [
            ['dataIndex' => 'ytd', 'title' => 'YTD', "align" => "right", 'width' => 100,],
            ['dataIndex' => 'unit', 'align' => 'center', 'width' => 100],
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
            'unit' => ($y->getUnit) ? $y->getUnit->name : "",
            'ytd' => (object) [
                'value' => number_format($ytd, 2),
                'cell_class' => "bg-text-400 text-right1",
            ],
        ];
    }
}

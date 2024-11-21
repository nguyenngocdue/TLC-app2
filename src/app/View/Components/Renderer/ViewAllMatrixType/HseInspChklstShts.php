<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Hse_insp_chklst_sht;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use App\View\Components\Renderer\ViewAllMatrixFilter\TraitFilterMonth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HseInspChklstShts extends ViewAllTypeMatrixParent
{
    use TraitFilterMonth;
    use TraitXAxisWeekly;
    // use TraitYAxisDiscipline;

    protected $viewportDate = null;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "start_date";
    protected $yAxis = Workplace::class;
    protected $dataIndexY = "workplace_id";
    protected $rotate45Width = 400;
    // protected $tableTrueWidth = true;
    // protected $headerTop = 20 * 16;
    protected $groupBy = null;
    // protected $mode = 'detail';
    protected $apiToCallWhenCreateNew = 'cloneTemplate';
    protected $nameColumnFixed = false;

    private $templateId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->viewportDate, $this->templateId] = $this->getUserSettings();
        $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        // [$this->selectedYear, $this->selectedQuarter,] = $this->getUserSettings();
        // $this->selectedYear = $this->selectedYear ?: date("Y");
        // $this->selectedQuarter = $this->selectedQuarter ?: 1;
        $this->templateId = $this->templateId ?: 1;
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        // $selectedYear = $settings[$type][Constant::VIEW_ALL]['matrix']['selected_year'] ?? null;
        // $selectedQuarter = $settings[$type][Constant::VIEW_ALL]['matrix']['selected_quarter'] ?? null;
        $viewportDate = $settings[$type][Constant::VIEW_ALL]['matrix']['viewport_date'] ?? null;
        $templateId = $settings[$type][Constant::VIEW_ALL]['matrix']['template_id'] ?? null;
        return [$viewportDate, $templateId];
    }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            ->get();
        return $yAxis;
    }

    public function getMatrixDataSource($xAxis)
    {
        $lines = Hse_insp_chklst_sht::query();
        $result = $lines->get();
        // dump($result);
        return $result;
    }

    protected function getViewportParams()
    {
        return [
            // 'selected_year' => $this->selectedYear,
            // 'selected_quarter' => $this->selectedQuarter,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';

        $params['tmpl_id'] = $this->templateId;
        $params['start_date'] = $x['dataIndex'];

        return $params;
    }
}

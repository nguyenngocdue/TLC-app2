<?php

namespace App\View\Components\Renderer\ViewAll;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class ViewAllTypeMatrix extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $view = null,
        // private $viewportDate = null,
        // private $viewportMode = 'week',
    ) {
        // $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        // if (!$this->viewportMode) $this->viewportMode = 'week';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        switch ($this->type) {
            case "hr_timesheet_workers":
                if ($this->view == 'approve-multi') return Blade::render("<x-renderer.view-all-matrix-type.HrTimesheetWorkersApproveMulti/>");
                return Blade::render("<x-renderer.view-all-matrix-type.HrTimesheetWorkers/>");
            case "qaqc_wirs":
                return Blade::render("<x-renderer.view-all-matrix-type.QaqcWirs/>");
            case "hr_onboarding_lines":
                return Blade::render("<x-renderer.view-all-matrix-type.HrOnboardingLines />");
            case "hse_extra_metrics":
                return Blade::render("<x-renderer.view-all-matrix-type.HseExtraMetrics/>");
            case "prod_sequences":
                if ($this->view == 'print') return Blade::render("<x-renderer.view-all-matrix-type.ProdSequencesPrint/>");
                return Blade::render("<x-renderer.view-all-matrix-type.ProdSequences/>");
            case "prod_orders":
                return Blade::render("<x-renderer.view-all-matrix-type.ProdOrders/>");
            case "ghg_sheets":
                return Blade::render("<x-renderer.view-all-matrix-type.GhgSheets/>");
            case "site_daily_assignments":
                return Blade::render("<x-renderer.view-all-matrix-type.SiteDailyAssignments/>");
            case "qaqc_insp_chklst_shts":
                if ($this->view == 'signature') return Blade::render("<x-renderer.view-all-matrix-type.QaqcInspChklstShtsSignature/>");
                return Blade::render("<x-renderer.view-all-matrix-type.QaqcInspChklstShts/>");
            case "qaqc_insp_chklsts":
                return Blade::render("<x-renderer.view-all-matrix-type.QaqcInspChklsts/>");
            case "hse_insp_chklst_shts":
                return Blade::render("<x-renderer.view-all-matrix-type.HseInspChklstShts/>");
            case "esg_master_sheets":
                return Blade::render("<x-renderer.view-all-matrix-type.EsgMasterSheets/>");
            case "esg_inductions":
                return Blade::render("<x-renderer.view-all-matrix-type.EsgInductions/>");
            case "exam_sheets":
                return Blade::render("<x-renderer.view-all-matrix-type.ExamSheets/>");
            case "pj_task_budgets":
                return Blade::render("<x-renderer.view-all-matrix-type.PjTaskBudgets/>");
            default:
                return "<div class='w-full p-4 border rounded mt-4 bg-gray-100 text-center'>Matrix view is not supported for " . $this->type . " (ViewAllTypeMatrix).<br/>Please use List View.</div>";
        }
    }
}

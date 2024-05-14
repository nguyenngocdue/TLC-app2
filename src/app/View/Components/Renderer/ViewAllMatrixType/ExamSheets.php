<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Exam_sheet;
use App\Models\Exam_tmpl;
use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExamSheets extends ViewAllTypeMatrixParent
{
    // use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "exam_tmpl_id";
    protected $yAxis = User::class;
    protected $dataIndexY = "owner_id";
    // protected $rotate45Width = 400;
    protected $tableTrueWidth = true;
    protected $headerTop = 20;
    protected $groupBy = 'department_for_group_by';
    protected $groupByLength = 1000;
    // protected $mode = 'status';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // [$this->project, $this->subProject, $this->prodRouting, $this->prodRoutingLink, $this->prodDiscipline] = $this->getUserSettings();
        // $this->project = $this->project ? $this->project : 5;
        // $this->subProject = $this->subProject ? $this->subProject : null;
        // $this->prodRouting = $this->prodRouting ? $this->prodRouting : null;
        // $this->prodRoutingLink = $this->prodRoutingLink ? $this->prodRoutingLink : [];
    }

    // private function getUserSettings()
    // {
    //     $type = Str::plural($this->type);
    //     $settings = CurrentUser::getSettings();
    //     $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
    //     $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
    //     $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
    //     $prodRoutingLink = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_link_id'] ?? null;
    //     $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
    //     return [$project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline];
    // }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            ->whereNot('resigned', 1)
            ->whereNot('show_on_beta', 1)
            ->whereNot('time_keeping_type', 1)
            ->where('workplace', 1)
            ->with('getUserDepartment')
            ->orderBy('name0')
            ->get();
        return $yAxis;
    }

    protected function getXAxisPrimaryColumns()
    {
        $status = (CurrentUser::isAdmin()) ? ['testing', 'publish'] : ['publish'];
        $data = Exam_tmpl::query()
            ->whereIn('status', $status);
        $data = $data->orderBy('name')->get();
        return $data;
    }

    protected function getXAxis()
    {
        $result = [];
        $data = $this->getXAxisPrimaryColumns();
        foreach ($data as $line) {
            $item = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name,
                'isExtra' => true,
            ];
            $result[] = $item;
        }
        return $result;
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        // return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $lines = Exam_sheet::query()->get();
        return $lines;
    }

    // protected function getViewportParams()
    // {
    //     return [
    //         'project_id' => $this->project,
    //         'sub_project_id' => $this->subProject,
    //         'prod_routing_id' => $this->prodRouting,
    //         'prod_routing_link_id' => $this->prodRoutingLink,
    //         'prod_discipline_id' => $this->prodDiscipline,
    //     ];
    // }

    // protected function getCreateNewParams($x, $y)
    // {
    //     $params = parent::getCreateNewParams($x, $y);
    //     $params['status'] =  'new';
    //     // $params['project_id'] =  $this->project;
    //     $params['sub_project_id'] =  $this->subProject;
    //     $params['prod_routing_id'] =  $this->prodRouting;

    //     // $params['prod_discipline_id'] =  $x['prod_discipline_id'];
    //     return $params;
    // }

    protected function getMetaColumns()
    {
        $columns = [
            [
                'dataIndex' => 'department_for_group_by',
                'hidden' => true,
            ],
        ];
        return $columns;
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        return [
            'department_for_group_by' => $y->getUserDepartment->name,
        ];
    }
}

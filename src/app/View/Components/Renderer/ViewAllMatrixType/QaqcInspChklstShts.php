<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QaqcInspChklstShts extends ViewAllTypeMatrixParent
{
    use TraitYAxisDiscipline;

    private $project, $qaqcInspTmpl, $subProject, $prodRouting, $prodDiscipline;
    // protected $viewportMode = null;

    protected $xAxis = Qaqc_insp_chklst_sht::class;
    protected $dataIndexX = "qaqc_insp_tmpl_sht_id";
    protected $yAxis = Qaqc_insp_chklst::class;
    protected $dataIndexY = "qaqc_insp_chklst_id";
    protected $rotate45Width = 200;
    protected $rotate45Height = 150;
    protected $tableTrueWidth = true;
    protected $headerTop = 40;
    protected $groupBy = null;
    protected $apiToCallWhenCreateNew = 'cloneTemplate';

    private static $punchlistStatuses = null;
    // protected $mode = 'status_only';
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, $this->qaqcInspTmpl, $this->subProject, $this->prodRouting, $this->prodDiscipline] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : 21;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : 2;
        $this->qaqcInspTmpl = $this->qaqcInspTmpl ? $this->qaqcInspTmpl : null;

        static::$punchlistStatuses = LibStatuses::getFor('qaqc_punchlists');
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $qaqcInspTmpl = $settings[$type][Constant::VIEW_ALL]['matrix']['qaqc_insp_tmpl_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
        return [$project, $qaqcInspTmpl, $subProject, $prodRouting, $prodDiscipline];
    }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmpl)
            ->where('sub_project_id', $this->subProject)
            // ->where('prod_routing_id', $this->prodRouting)
            ->with('getProdOrder')
            ->with('getPunchlist')
            ->orderBy('name')
            ->get();
        // dump($yAxis[0]);
        return $yAxis;
    }

    protected function getXAxis()
    {
        $result = [];
        if (is_null($this->qaqcInspTmpl)) {
            echo Blade::render("<x-feedback.alert type='error' message='You must specify Checklist Type.'></x-feedback.alert>");
            return [];
        }
        $data = Qaqc_insp_tmpl::find($this->qaqcInspTmpl)
            ->getSheets()
            ->orderBy('order_no')
            ->get();
        // dump($data[0]);      
        foreach ($data as $line) {
            $result[] = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name,
                'align' => 'center',
                'width' => 40,
                'prod_discipline_id' => $line->prod_discipline_id,
                // 'default_monitors' => ($line->getMonitors1())->pluck('name'),
            ];
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $result = Qaqc_insp_chklst_sht::query()
            // ->where('qaqc_insp_tmpl_id', $this->qaqcInspTmpl)
            ->get();
        return $result;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'qaqc_insp_tmpl_id' => $this->qaqcInspTmpl,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            'prod_discipline_id' => $this->prodDiscipline,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        // $params['project_id'] =  $this->qaqcInspTmpl;
        $params['sub_project_id'] =  $this->subProject;
        $params['prod_routing_id'] =  $this->prodRouting;

        // $params['prod_discipline_id'] =  $x['prod_discipline_id'];
        return $params;
    }

    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'compliance_name', /* 'width' => 300, /*'fixed' => 'left',*/],
            ['dataIndex' => 'progress', "title" => 'Progress (%)', 'align' => 'right', 'width' => 50,/* 'fixed' => 'left',*/],
            ['dataIndex' => 'print', "title" => 'Print', 'align' => 'right', 'width' => 50,/* 'fixed' => 'left',*/],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel)
    {
        if (isset($y->getPunchlist[0])) {
            $route = route("qaqc_punchlists.edit", $y->getPunchlist[0]->id);
            $status_object = $this->makeStatus($y, false, $route, static::$punchlistStatuses);
        } else {
            $apiToCallWhenCreateNew = "storeEmpty";
            $api_url = route("qaqc_punchlists." . $apiToCallWhenCreateNew);
            $line = [];
            $this->makeCreateButton($xAxis, $y, [], $line, $api_url, $apiToCallWhenCreateNew);
            // dump($line);
            // $route = route("qaqc_punchlists.create");
            // $status_object = (object) [
            //     'value' => '<i class="fa-duotone fa-circle-plus"></i>',
            //     'cell_href' => $route,
            //     'cell_class' => "text-center text-blue-800",
            //     'cell_title' => "Create a new document",
            // ];
        }

        // $status_object->cell_href = route("qaqc_punchlists" . ".edit", $y->id);

        $compliance_name = $y->getProdOrder->compliance_name ?: "";
        $print_link = route("qaqc_insp_chklsts.show", $y->id);
        $result = [
            'compliance_name' => (object)[
                'value' => $compliance_name,
                'cell_class' => 'whitespace-nowrap'
            ],
            'progress' => $y->progress ?: 0,
            'print' => "<a href='$print_link'><i class='fa-duotone fa-print text-blue-600'/></a>",
            'final_punchlist' =>  '$status_object',
        ];

        return $result;
    }

    function getRightMetaColumns()
    {
        return [
            // ['dataIndex' => 'final_punchlist',],
        ];
    }
}

<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_tmpl;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QaqcInspChklsts extends ViewAllTypeMatrixParent
{
    // use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting, $prodDiscipline;
    // protected $viewportMode = null;

    protected $xAxis = Qaqc_insp_tmpl::class;
    protected $dataIndexX = "qaqc_insp_tmpl_id";
    protected $yAxis = Prod_order::class;
    protected $dataIndexY = "prod_order_id";
    protected $rotate45Width = 200;
    protected $rotate45Height = 150;
    protected $tableTrueWidth = true;
    protected $headerTop = 20;
    protected $groupBy = null;
    protected $apiCallback = "()=>location.reload()";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, $this->subProject, $this->prodRouting, $this->prodDiscipline] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : 21;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : 2;
        // $this->prodDiscipline = $this->prodDiscipline ? $this->prodDiscipline : 2;
        // dump($this->project, $this->subProject, $this->prodRouting);
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
        return [$project, $subProject, $prodRouting, $prodDiscipline];
    }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            // ->where('qaqc_insp_tmpl_id', $this->project)
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            // ->with('getProdOrder')
            ->orderBy('name')
            ->get();
        return $yAxis;
    }

    protected function getXAxis()
    {
        $result = [];
        $data = Qaqc_insp_tmpl::query()
            ->with("getProdRoutingsOfInspTmpl")
            ->orderBy('order_no')
            ->get();
        // dump($data[0]);
        foreach ($data as $line) {
            $supported = $line->getProdRoutingsOfInspTmpl->pluck('id')->toArray();
            if (!in_array($this->prodRouting, $supported)) continue;
            $result[] = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name,
                'description' => $line->description,
                'align' => 'center',
                'width' => 40,
            ];
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $result = Qaqc_insp_chklst::query()->get();
        // dump($result);
        return $result;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            'prod_discipline_id' => $this->prodDiscipline,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        // $shortName = $x['description'];
        $params['name'] = $y->name; //. " ($shortName)";
        $params['status'] =  'new';
        // $params['project_id'] =  $this->project;
        $params['sub_project_id'] =  $this->subProject;
        $params['prod_routing_id'] =  $this->prodRouting;

        // $params['prod_discipline_id'] =  $x['prod_discipline_id'];
        return $params;
    }

    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'compliance_name',  'width' => 300, /*'fixed' => 'left',*/],
            ['dataIndex' => 'description', 'width' => 50,/* 'fixed' => 'left',*/],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        $status_object = $this->makeStatus($y, false);
        $status_object->cell_href = route("prod_orders" . ".edit", $y->id);
        $result = [
            'compliance_name' => (object)[
                'value' => $y->compliance_name,
                'cell_div_class' => 'p-2 whitespace-nowrap',
            ],
            'description' => (object)[
                'value' => $y->description,
                'cell_div_class' => 'p-2 whitespace-nowrap',
            ],
        ];

        return $result;
    }

    protected function makeStatus($document, $forExcel, $editRoute = null, $statuses = null, $objectToGet = null, $matrixKey = null)
    {
        $params = [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            'qaqc_insp_tmpl_id' => $document->qaqc_insp_tmpl_id,
        ];
        $route = route("qaqc_insp_chklst_shts.index", $params);
        return parent::makeStatus($document, $forExcel, $route);
    }
}

<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\BigThink\Oracy;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Prod_routing;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Models\Sub_project;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FakeQaqcPunchlist
{
    public $type = 'qaqc_punchlists';
    public $apiToCallWhenCreateNew = "storeEmpty";
    public $attOfCellToRender = 'status';
    public function getCreateNewParams($x, $y)
    {
        return [
            'project_id' => $y->getProject->id,
            'sub_project_id' => $y->sub_project_id,
            'status' => 'new',
            'qaqc_insp_chklst_id' => $y->id,
            'production_name' => $y->getProdOrder->production_name,
        ];
    }
}

class QaqcInspChklstShts extends ViewAllTypeMatrixParent
{
    use TraitYAxisDiscipline;

    private $project, /*$qaqcInspTmpl,*/ $subProject, $prodRouting;
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
    protected $maxH = 60;
    protected $multipleMatrix = true;

    private static $punchlistStatuses = null;
    private $fakeQaqcPunchlistObj;
    private $hasPunchlist = false;
    // protected $mode = 'status_only';
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, /*$this->qaqcInspTmpl,*/ $this->subProject, $this->prodRouting] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : 21;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : null;
        // $this->qaqcInspTmpl = $this->qaqcInspTmpl ? $this->qaqcInspTmpl : null;

        static::$punchlistStatuses = LibStatuses::getFor('qaqc_punchlists');
        $this->fakeQaqcPunchlistObj = new FakeQaqcPunchlist();

        $this->matrixes = $this->getMultipleMatrixObjects();
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        // $qaqcInspTmpl = $settings[$type][Constant::VIEW_ALL]['matrix']['qaqc_insp_tmpl_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        return [$project, /*$qaqcInspTmpl,*/ $subProject, $prodRouting];
    }

    public function getYAxis()
    {
        $result = [];
        foreach ($this->matrixes as $key => $matrix) {
            $tmplId = $matrix['chklst_tmpls']->id;
            $routingId = $matrix['routing']->id;
            $yAxis = $this->yAxis::query()
                ->where('sub_project_id', $this->subProject)
                ->where('qaqc_insp_tmpl_id', $tmplId)
                //This to enable AOI 1 Mockup Backup
                ->where('prod_routing_id', $routingId)
                ->with('getProdOrder')
                ->with('getPunchlist')
                ->orderBy('name');
            $result[$key] = $yAxis->get();
            // dump(sizeof($yAxis));
        }
        return $result;
    }

    protected function getXAxis()
    {
        $result = [];
        foreach ($this->matrixes as $key => $matrix) {
            $columns = [];
            // if (is_null($this->qaqcInspTmpl)) {
            //     echo Blade::render("<x-feedback.alert type='error' message='You must specify Checklist Type.'></x-feedback.alert>");
            //     return [];
            // }

            $data = Qaqc_insp_tmpl_sht::query()
                ->where('qaqc_insp_tmpl_id', $matrix['chklst_tmpls']->id)
                ->with('getProdDiscipline')
                ->orderBy('order_no')
                ->get();

            // $data = Qaqc_insp_tmpl::find($matrix['chklst_tmpls']->id)
            //     ->getSheets()
            //     ->orderBy('order_no')                
            //     ->get();
            // dump($data[0]);      
            foreach ($data as $line) {
                $columns[] = [
                    'dataIndex' => $line->id,
                    'columnIndex' => "status",
                    'title' => $line->name,
                    'align' => 'center',
                    'width' => 40,
                    'discipline_description' => $line->getProdDiscipline->description,
                    'discipline_css_class' => $line->getProdDiscipline->css_class,
                    // 'prod_discipline_id' => $line->prod_discipline_id,
                    // 'default_monitors' => ($line->getMonitors1())->pluck('name'),
                ];
            }

            if ($matrix['chklst_tmpls']->has_punchlist) {
                $QAQC_DISCIPLINE_ID = 7;
                $columns = [
                    ...$columns,
                    ['dataIndex' => 'final_punchlist', 'prod_discipline_id' => $QAQC_DISCIPLINE_ID],
                ];
            }
            $result[$key] = $columns;
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        // dd($result);
        return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $result = [];
        foreach ($this->matrixes as $key => $matrix) {
            $tmplId = $matrix['chklst_tmpls']->id;
            $item = Qaqc_insp_chklst_sht::whereHas('getTmplSheet.getTmpl', function ($query) use ($tmplId) {
                $query->where('qaqc_insp_tmpl_id', $tmplId);
            })
                ->with('signature_qaqc_chklst_3rd_party')
                ->get();
            Oracy::attach("signature_qaqc_chklst_3rd_party_list()", $item);
            $result[$key] = $item;
        }
        // dump($result);
        // dd();
        return $result;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            // 'qaqc_insp_tmpl_id' => $this->qaqcInspTmpl,
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

    protected function getCreateNewButton($y)
    {
        $line = [];
        $fakeXAxis = [['dataIndex' => "punchlistColumn"]];
        $this->makeCreateButton($fakeXAxis, $y, [], $line, $this->fakeQaqcPunchlistObj);
        return $line['punchlistColumn'];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel)
    {
        if (!isset($y->getPunchlist[0])) {
            $status_object = $this->getCreateNewButton($y);
        } else {
            $document = $y->getPunchlist[0];
            $route = route($this->fakeQaqcPunchlistObj->type . ".edit", $document->id);

            $status_object = $this->makeStatus($document, false, $route, static::$punchlistStatuses, $this->fakeQaqcPunchlistObj, 'key_ahihi');
        }

        $compliance_name = $y->getProdOrder->compliance_name ?: "";
        $print_link = route("qaqc_insp_chklsts.show", $y->id);
        $result = [
            'compliance_name' => (object)[
                'value' => $compliance_name,
                'cell_class' => 'whitespace-nowrap'
            ],
            'progress' => $y->progress ?: 0,
            'print' => "<a href='$print_link'><i class='fa-duotone fa-print text-blue-600'/></a>",
            'final_punchlist' =>  $status_object,
        ];

        return $result;
    }

    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $x) {
            if (isset($x['discipline_description'])) {
                $item = (object)[
                    'value' => $x['discipline_description'],
                    'cell_class' => $x['discipline_css_class'],
                ];
                $result[$x['dataIndex']] = $item;
            }
        }
        return $result;
    }

    protected function getMultipleMatrixObjects()
    {
        $show_on_ics_id = config("production.prod_routings.qaqc_insp_chklsts");
        if ($this->prodRouting) {
            $prodRoutings = [Prod_routing::find($this->prodRouting)];
        } else {
            $prodRoutings = Sub_project::find($this->subProject)->getProdRoutingsOfSubProject();
        }
        $result = [];
        foreach ($prodRoutings as $key => $routing) {
            $showOnScreenIds = $routing->getScreensShowMeOn()->pluck('id');
            if ($showOnScreenIds->contains($show_on_ics_id)) {
                $tmpls = $routing->getChklstTmpls();
                if ($tmpls->count() > 0) {
                    foreach ($tmpls as $tmpl) {
                        $key = $routing->id . "_" . $tmpl->id;
                        $result[$key] = [
                            'name'  => $routing->name,
                            'description' => "Checklist Type: " . $tmpl->name,
                            'routing' =>   $routing,
                            'chklst_tmpls' => $tmpl,
                        ];
                    }
                }
            }
        }
        // dump($result);
        return $result;
    }
}

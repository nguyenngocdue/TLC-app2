<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Pj_module;
use App\Models\Pj_unit;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Project;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Models\Qaqc_ncr;
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
            'project_id' => $y->getProject?->id,
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

    private $projectId, $subProjectId, $prodRoutingId;
    protected $projectDatasource, $subProjectDatasource, $prodRoutingDatasource, $prodRoutingMatrixDatasource;
    private $QAQC_DISCIPLINE_ID = 7;

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

    protected $matrixDataSourceSingleton = null;

    // protected $metaShowPrint = true;
    protected $metaShowProgress = true;
    protected $showNameColumn = false;

    private static $punchlistStatuses = null;
    private $fakeQaqcPunchlistObj;
    private $hasPunchlist = false;

    protected $SANDBOX_PROJECT_ID = 72;
    protected $SANDBOX_SUB_PROJECT_ID = 112;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct("qaqc_insp_chklst_shts");
        [$this->projectId, $this->subProjectId, $this->prodRoutingId] = $this->getUserSettings();
        $this->projectId = $this->projectId ? $this->projectId : $this->SANDBOX_PROJECT_ID;
        $this->subProjectId = $this->subProjectId ? $this->subProjectId : $this->SANDBOX_SUB_PROJECT_ID;
        $this->prodRoutingId = $this->prodRoutingId ? $this->prodRoutingId : null;

        static::$punchlistStatuses = LibStatuses::getFor('qaqc_punchlists');
        $this->fakeQaqcPunchlistObj = new FakeQaqcPunchlist();

        $this->matrixes = $this->getMultipleMatrixObjects();

        // dump($this->getProjectListForFilter());
        // dump($this->getSubProjectListForFilter());
        // dump($this->getRoutingListForFilter());

        // dump($this->getRoutingListForMatrix());
    }

    protected function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        return [$project, $subProject, $prodRouting];
    }

    public function getYAxis()
    {
        $result = [];
        foreach ($this->matrixes as $key => $matrix) {
            $tmplId = $matrix['chklst_tmpls']->id;
            $routingId = $matrix['routing']->id;
            $yAxis = $this->yAxis::query()
                ->where('sub_project_id', $this->subProjectId)
                ->where('qaqc_insp_tmpl_id', $tmplId)
                //This to enable AOI 1 Mockup Backup
                ->where('prod_routing_id', $routingId)
                ->with([
                    'getProdOrder',
                    'getPunchlist',
                    'getProdOrder.getSubProject'
                ])
                ->orderBy('name');
            $result[$key] = $yAxis->get();
            // dump(sizeof($yAxis));
        }
        return $result;
    }

    protected function getXAxis()
    {
        $result = [];

        $qaqc_discipline = Prod_discipline::find($this->QAQC_DISCIPLINE_ID);
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
                    'discipline_description' => $line->getProdDiscipline?->description,
                    'discipline_css_class' => $line->getProdDiscipline?->css_class,
                    // 'prod_discipline_id' => $line->prod_discipline_id,
                    // 'default_monitors' => ($line->getMonitors1)->pluck('name'),
                ];
            }

            // $routingId = $matrix['routing']->id;
            // $SHIPPING_CONTAINER_ID = 57;
            // if ($routingId != $SHIPPING_CONTAINER_ID) {
            if (!$matrix['chklst_tmpls']->hide_ncr_count) {
                $columns = [
                    ...$columns,
                    [
                        'dataIndex' => 'ncr_count',
                        'title' => 'NCR Count',
                        'width' => 40,
                        'discipline_description' => $qaqc_discipline->description,
                        'discipline_css_class' => $qaqc_discipline->css_class,
                    ],
                ];
            }

            if ($matrix['chklst_tmpls']->has_punchlist) {
                $columns = [
                    ...$columns,
                    [
                        'dataIndex' => 'final_punchlist',
                        'width' => 40,
                        'discipline_description' => $qaqc_discipline->description,
                        'discipline_css_class' => $qaqc_discipline->css_class,
                    ],
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
        if (is_null($this->matrixDataSourceSingleton)) {
            $result = [];
            foreach ($this->matrixes as $key => $matrix) {
                $tmplId = $matrix['chklst_tmpls']->id;
                $item = Qaqc_insp_chklst_sht::whereHas('getTmplSheet.getTmpl', function ($query) use ($tmplId) {
                    $query->where('qaqc_insp_tmpl_id', $tmplId);
                })
                    ->with('signature_qaqc_chklst_3rd_party')
                    ->with('signature_qaqc_chklst_3rd_party_list')
                    ->get();
                // Oracy::attach("signature_qaqc_chklst_3rd_party_list()", $item);
                $result[$key] = $item;
            }
            // dump($result);
            // dd();
            $this->matrixDataSourceSingleton = $result;
            // } else {
            //     echo "CACHE HIT - getMatrixDataSource";
        }
        return $this->matrixDataSourceSingleton;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->projectId,
            'sub_project_id' => $this->subProjectId,
            'prod_routing_id' => $this->prodRoutingId,
            // 'qaqc_insp_tmpl_id' => $this->qaqcInspTmpl,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        // $params['project_id'] =  $this->qaqcInspTmpl;
        $params['sub_project_id'] =  $this->subProjectId;
        $params['prod_routing_id'] =  $this->prodRoutingId;

        // $params['prod_discipline_id'] =  $x['prod_discipline_id'];
        return $params;
    }

    protected function getMetaColumns()
    {
        $result = [];
        $result[] = [
            'dataIndex' => 'name',
            'align' => 'right',
            'width' => 50,
            /* 'fixed' => 'left',*/
        ];
        $result[] = ['dataIndex' => 'compliance_name', 'width' => 300,];
        $result[] = ['dataIndex' => 'description', 'width' => 50,];
        if ($this->metaShowProgress) $result[] = [
            'dataIndex' => 'progress',
            "title" => 'Progress (%)',
            'align' => 'right',
            'width' => 50,
            'footer' => 'agg_avg',
            /* 'fixed' => 'left',*/
        ];
        return $result;
    }

    protected function getCreateNewButton($y)
    {
        $line = [];
        $fakeXAxis = [['dataIndex' => "punchlistColumn"]];
        $this->makeCreateButton($fakeXAxis, $y, [], $line, $this->fakeQaqcPunchlistObj);
        return $line['punchlistColumn'];
    }

    private function makeStatusObjectForPunchlist($y, $matrixKey)
    {
        if (!isset($y->getPunchlist[0])) {
            $status_object = $this->getCreateNewButton($y);
        } else {
            $document = $y->getPunchlist[0];
            $route = route($this->fakeQaqcPunchlistObj->type . ".edit", $document->id);

            $status_object = $this->makeStatus($document, false, $route, static::$punchlistStatuses, $this->fakeQaqcPunchlistObj, $matrixKey);
        }
        return $status_object;
    }

    // private function makeOpenPrintPageWhenClickName($y)
    // {
    //     $name = [
    //         'value' => $y->name,
    //         'cell_title' => "(#" . $y->id . ")",
    //     ];

    //     if ($this->metaShowPrint) {
    //         $name['cell_href'] = route("qaqc_insp_chklsts.show", $y->id);
    //         $name['cell_class'] = "text-blue-800 bg-white";
    //         $name['cell_div_class'] = 'p-2 whitespace-nowrap cursor-pointer';
    //     } else {
    //         $name['cell_div_class'] = 'p-2 whitespace-nowrap';
    //         $name['cell_class'] = "bg-white";
    //     }
    //     return $name;
    // }

    private function makeIdStatusLink($prod_order_id, $product_id = null)
    {
        $ncrList = Qaqc_ncr::query()
            ->where('prod_order_id', $prod_order_id)
            ->get();
        $count = count($ncrList);
        $label = $count;
        if ($product_id) {
            if ($count) $label = " - Module $product_id: " . $count . ($count == 1 ? " NCR" : " NCRs");
            else $label = "";
        }

        $content = Blade::render('<x-renderer.id_status_link :dataSource="$ncrList" showTitle=1 />', [
            'ncrList' => $ncrList,
        ]);
        return [$label, $count, $content,];
    }

    private function makeNcrCount($y)
    {
        $prodOrder = $y->getProdOrder;
        $prod_order_id = $prodOrder->id;
        $product_type = $prodOrder->meta_type;
        $product_id = $prodOrder->meta_id;
        $content = "";
        switch ($product_type) {
            case Pj_unit::class:
                $unit = Pj_unit::query()
                    ->where('id', $product_id)
                    ->with(['getPjModules' => function ($query) {
                        $query->with('getProdOrders');
                    }])
                    ->first();

                $contents = [];
                $sum = 0;
                foreach ($unit->getPjModules as $module) {
                    $prodOrder = $module->getProdOrders;
                    foreach ($prodOrder as $order) {
                        [$label, $count, $content] = $this->makeIdStatusLink($order->id, $module->name);
                        $contents[] = $label . " " . $content;
                        $sum += $count;
                    }
                }

                $label = $sum;
                $content = join(" ", $contents);
                break;
            case Pj_module::class:
            default:
                [$label, $count, $content] = $this->makeIdStatusLink($prod_order_id);
                break;
        }

        $ncr_count = '<div class="text-center"><span class="font-bold ">0</span></div>';
        if ($label) {
            $ncr_count = Blade::render('
        <button data-popover-target="popover-{{$id}}" data-popover-placement="right" type="button" class="font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
        {!! $label !!}
        </button>
        <x-renderer.popover id="popover-{{$id}}" title="{!! $title !!}" content="{!! $content !!}"/>
        ', [
                'id' => $y->id,
                'label' => "<b>" . $label . "</b>",
                'title' => "NCR Summary",
                'content' => $content,
            ]);
        }
        return $ncr_count;
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        $status_object = $this->makeStatusObjectForPunchlist($y, $matrixKey);
        $compliance_name = $y->getProdOrder->compliance_name ?: "";
        $description = $y->getProdOrder->description ?: "";
        // $name = $this->makeOpenPrintPageWhenClickName($y);
        $ncr_count = $this->makeNcrCount($y);

        $result = [
            // 'name' => $y->name,
            'name' => (object)[
                'value' => $y->name,
                'cell_class' => 'whitespace-nowrap'
            ],
            'compliance_name' => (object)[
                'value' => $compliance_name,
                'cell_class' => 'whitespace-nowrap text-blue-800 bg-white',
                'cell_href' => route("qaqc_insp_chklsts.show", $y->id),
            ],
            'description' => (object)[
                'value' => $description,
                'cell_class' => 'whitespace-nowrap'
            ],
            'progress' => $y->progress ?: 0,
            'ncr_count' => (object)[
                "value" => $ncr_count,
                "cell_div_class" => "whitespace-nowrap",
            ],
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
        if ($this->prodRoutingId) {
            $prodRoutings = [Prod_routing::find($this->prodRoutingId)];
        } else {
            $prodRoutings = $this->getRoutingListForMatrix();
        }
        $result = [];
        foreach ($prodRoutings as $key => $routing) {
            $allSubProjects = $routing->getSubProjects;
            if ($allSubProjects) {
                if (is_array($allSubProjects)) {
                    if (in_array($this->subProjectId, $allSubProjects)) continue;
                } else {
                    if (!$allSubProjects->contains($this->subProjectId)) continue;
                }
            }
            $showOnScreenIds = $routing->getScreensShowMeOn->pluck('id');
            if ($showOnScreenIds->contains($show_on_ics_id)) {
                $tmpls = $routing->getChklstTmpls()->orderBy('order_no')->get();
                if ($tmpls->count() > 0) {
                    foreach ($tmpls as $tmpl) {
                        $key = $routing->id . "_xyz_xyz_" . $tmpl->id . "_order_no_" . $tmpl->order_no;
                        $result[$key] = [
                            'name'  => $routing->name . " (" . $tmpl->short_name . ")",
                            'description' => "", //"Checklist Type: 111 " . $tmpl->name,
                            'routing' =>   $routing,
                            'chklst_tmpls' => $tmpl,
                            'order_no' => $tmpl->order_no,
                            'sub_project_id' => $this->subProjectId,
                        ];
                    }
                }
            }
        }
        uasort($result, function ($a, $b) {
            return $a['order_no'] <=> $b['order_no'];
        });
        // dump($result);
        return $result;
    }

    protected function getRoutingListForMatrix()
    {
        if (!$this->prodRoutingMatrixDatasource) {
            $routings = Sub_project::find($this->subProjectId)
                ->getProdRoutingsOfSubProject()
                ->get();
            // dump($routings);
            $allowList = $this->getRoutingListForFilter();
            // dump($allowList);
            $this->prodRoutingMatrixDatasource = $routings->intersect($allowList);
        } else {
            // echo "CACHE HIT - getRoutingListForMatrix";
        }
        return $this->prodRoutingMatrixDatasource;
    }

    protected function enrichRouting($prodRoutings)
    {
        //Remove routings that are not allow to show on the screen
        $prodRoutings = $prodRoutings->filter(fn($item) => $item->isShowOn("qaqc_insp_chklst_shts"))->values();

        //Enrich for listeners sub projects -> routing
        foreach ($prodRoutings as &$item) {
            $item->{"getSubProjects"} = $item->getSubProjects->pluck('id')->toArray();
        }

        return $prodRoutings;
    }

    protected function getRoutingListForFilter()
    {
        if (!$this->prodRoutingDatasource) {
            $subProjectDatasource = $this->getSubProjectListForFilter();
            $routingIds = [];
            foreach ($subProjectDatasource as $subProject) {
                $routingIds[] = $subProject->getProdRoutingsOfSubProject->pluck('id')->toArray();
            }
            $routingIds = array_unique(array_merge(...$routingIds));
            $prodRoutings = Prod_routing::query()
                ->whereIn('id', $routingIds)
                ->with([
                    "getSubProjects",
                    "getScreensShowMeOn",
                ])
                ->get();

            $prodRoutings = $this->enrichRouting($prodRoutings);
            $this->prodRoutingDatasource = $prodRoutings;
        } else {
            // echo "CACHE HIT - getRoutingListForFilter";
        }
        return $this->prodRoutingDatasource;
    }

    protected function getSubProjectListForFilter()
    {
        if (!$this->subProjectDatasource) {
            $this->subProjectDatasource = Sub_project::all();
        } else {
            // echo "CACHE HIT - getSubProjectListForFilter";
        }
        return $this->subProjectDatasource;
    }

    protected function getProjectListForFilter()
    {
        if (!$this->projectDatasource) {
            $subProjectDatasource = $this->getSubProjectListForFilter();
            $projectIds = [];
            foreach ($subProjectDatasource as $subProject) {
                $projectIds[] = $subProject->project_id;
            }
            $this->projectDatasource = Project::query()
                ->whereIn('id', $projectIds)
                ->get();
        } else {
            // echo "CACHE HIT - getProjectListForFilter";
        }
        return $this->projectDatasource;
    }

    protected function getFilterDataSource()
    {
        return [
            'projects' => $this->getProjectListForFilter(),
            'sub_projects' => $this->getSubProjectListForFilter(),
            'prod_routings' => $this->getRoutingListForFilter(),
        ];
    }
}

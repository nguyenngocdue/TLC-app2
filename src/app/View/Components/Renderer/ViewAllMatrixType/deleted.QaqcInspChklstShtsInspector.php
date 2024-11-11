<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShtsInspector extends QaqcInspChklstShts
{
    use QaqcInspChklstShtsTraits;

    // protected $metaShowPrint = !true;
    protected $metaShowProgress = !true;

    protected $allowCreation = false;

    private $dataSource = null;
    private $projects, $subProjects, $prodRoutings;

    protected $nominatedFn = "signature_qaqc_chklst_3rd_party_list";
    protected $getSubProjectsOfUserFn = "getSubProjectsOfExternalInspector";
    protected $getProdRoutingsOfUserFn = "getProdRoutingsOfExternalInspector";

    function  __construct()
    {
        $this->dataSource =  $this->getDataSource();
        parent::__construct();
    }

    private function getDataSource()
    {
        $cu = CurrentUser::get();
        $this->subProjects = $cu->{$this->getSubProjectsOfUserFn};
        if (!$this->subProjects->contains('id', $this->STW_SANDBOX_ID)) {
            $this->subProjects->prepend(Sub_project::findFromCache($this->STW_SANDBOX_ID));
        }

        $this->prodRoutings = $this->getRoutingListForFilter();
        $this->projects = $this->getProjectCollectionFromSubProjects();
        $result = [$this->projects, $this->subProjects, $this->prodRoutings];
        // dump($result);
        return $result;
    }

    protected function getFilterDataSource()
    {
        [$projects, $subProjects, $prodRoutings] = $this->dataSource;
        return [
            'projects' => $projects,
            'sub_projects' => $subProjects,
            'prod_routings' => $prodRoutings,
        ];
    }

    protected function getUserSettings()
    {
        $userSettings = parent::getUserSettings();
        // dump($userSettings);
        [$project_id, $sub_project_id, $prod_routing_id] = $userSettings;
        [$projects, $subProjects, $prodRoutings] = $this->dataSource;
        $defaultProjects = (sizeof($projects) > 0) ? $projects->first()->id : 72;
        $defaultSubProject = (sizeof($subProjects) > 0) ? $subProjects->first()->id : 112;
        $defaultProdRouting = null;
        $result = [
            $project_id ?: $defaultProjects,
            $sub_project_id ?: $defaultSubProject,
            $prod_routing_id ?: $defaultProdRouting,
        ];
        // dump($result);
        return $result;
    }

    protected function getViewportParams()
    {
        $userSettings = $this->getUserSettings();
        [$project_id, $sub_project_id, $prod_routing_id] = $userSettings;
        $result = [
            'project_id' => $project_id,
            'sub_project_id' => $sub_project_id,
            'prod_routing_id' => $prod_routing_id,
        ];
        // dump($result);
        return $result;
    }

    protected function getRoutingListForFilter()
    {
        $cu = CurrentUser::get();
        $prodRoutings = $cu
            ->{$this->getProdRoutingsOfUserFn}()
            ->with("getSubProjects")
            ->get();

        foreach ($prodRoutings as &$item) {
            $item->{"getSubProjects"} = $item->getSubProjects->pluck('id')->toArray();
        }

        // dump($prodRoutings);
        return $prodRoutings;
    }

    private static $matrixDataSourceSingleton = null;
    public function getMatrixDataSource($xAxis)
    {
        // dump($this->matrixes);
        if (is_null(static::$matrixDataSourceSingleton)) {
            $cuid = CurrentUser::id();
            // dump($this->matrixes);

            $result = [];
            foreach ($this->matrixes as $key => $matrix) {
                $result[$key] = [];
                $routingId = $matrix['routing']->id;
                $subProjectId = $matrix['sub_project_id'];
                $sheets = Qaqc_insp_chklst_sht::query()
                    ->whereHas('getChklst', function ($query) use ($routingId, $subProjectId) {
                        $query
                            ->where('prod_routing_id', $routingId)
                            ->where('sub_project_id', $subProjectId);
                    })
                    ->with($this->nominatedFn)
                    ->get();
                // Oracy::attach($this->nominatedFn . "()", $sheets);

                foreach ($sheets as $sheet) {
                    $extInsp = $sheet->{$this->nominatedFn};
                    if ($extInsp->contains($cuid)) {
                        $result[$key][] = $sheet;
                    }
                }
            }
            static::$matrixDataSourceSingleton = $result;
        }
        // dump($result);
        return static::$matrixDataSourceSingleton;
    }

    protected function getXAxis()
    {
        $xAxis = parent::getXAxis();
        $sheets = $this->getMatrixDataSource([]);
        foreach ($this->matrixes as $key => $matrix) {
            if (isset($sheets[$key])) {
                $allowX = array_map(fn($x) => $x['qaqc_insp_tmpl_sht_id'], $sheets[$key]);
                $xAxis[$key] = array_filter($xAxis[$key], fn($x) => in_array($x['dataIndex'], $allowX));
            } else {
                $xAxis[$key] = [];
            }
        }

        // dump($xAxis);
        return $xAxis;
    }

    public function getYAxis()
    {
        $yAxis = parent::getYAxis();
        $sheets = $this->getMatrixDataSource([]);
        foreach ($this->matrixes as $key => $matrix) {
            if (isset($sheets[$key])) {
                $allowY = array_map(fn($x) => $x['qaqc_insp_chklst_id'], $sheets[$key]);
                $yAxis[$key] = $yAxis[$key]->filter(fn($x) => in_array($x['id'], $allowY));
            } else {
                $yAxis[$key] = [];
            }
        }

        // dump($yAxis);
        return $yAxis;
    }
}

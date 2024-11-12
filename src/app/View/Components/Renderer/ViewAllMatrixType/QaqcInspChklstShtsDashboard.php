<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Http\Controllers\Workflow\LibDashboards;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShtsDashboard extends QaqcInspChklstShts
{
    private $dashboardConfig;
    protected $allowCreation = false;

    public function __construct(
        private $controller,
    ) {
        $cu = CurrentUser::get();
        $this->dashboardConfig = LibDashboards::getAll()[$cu->discipline] ?? null;
        if (!$this->dashboardConfig) {
            abort(404, "Your discipline [#" . ($cu->discipline ?? "?") . "] is not supported for this dashboard - Manage Dashboards.");
        }
        parent::__construct();

        $this->metaShowProgress = isset($this->dashboardConfig['show_column_progress']);
    }

    private function getNominatedFns()
    {
        $result = [];
        if (isset($this->dashboardConfig["show_some_ics_by_sign_off_box"])) {
            $result[] = "signature_qaqc_chklst_3rd_party_list";
        }
        if (isset($this->dashboardConfig["show_some_ics_by_council_member_box"])) {
            $result[] = "council_member_list";
        }
        if (isset($this->dashboardConfig["show_some_ics_by_shipping_agent_box"])) {
            $result[] = "shipping_agent_list";
        }
        return $result;
    }

    protected function getSubProjectListForFilter()
    {
        $cu = CurrentUser::get();
        if (!$this->subProjectDatasource) {
            $sub1 = collect();
            if (isset($this->dashboardConfig['show_some_ics_by_shipping_agent_box'])) {
                $sub1 = $cu->getSubProjectsOfShippingAgent;
            }

            $sub2 = collect();
            if (isset($this->dashboardConfig['show_some_ics_by_sign_off_box'])) {
                $sub2 = $cu->getSubProjectsOfExternalInspector;
            }

            $sub3 = collect();
            if (isset($this->dashboardConfig['show_some_ics_by_council_member_box'])) {
                $sub3 = $cu->getSubProjectsOfCouncilMember;
            }

            $sub4 = collect();
            if (isset($this->dashboardConfig['show_all_ics_by_sub_project_client_box'])) {
                $sub4 = $cu->getSubProjectsOfProjectClient;
            }

            // $defaultSubProjects = Sub_project::query()
            //     ->whereIn('id', [$this->SANDBOX_SUB_PROJECT_ID])
            //     ->get();

            $all = collect([...$sub1, ...$sub2, ...$sub3, ...$sub4, /*...$defaultSubProjects */]);

            $this->subProjectDatasource =  $all->unique('id')->values();
            // } else {
            //     echo "CACHE HIT - getSubProjectListForFilter";
        }
        return $this->subProjectDatasource;
    }

    protected function getRoutingListForFilter()
    {
        if (!$this->prodRoutingDatasource) {
            $parentRoutings = parent::getRoutingListForFilter();
            $this->prodRoutingDatasource = $parentRoutings;
            if (isset($this->dashboardConfig["show_some_routings_per_user"])) {
                $cu = CurrentUser::get();
                $whiteList = $cu->getProdRoutingsPerUser;
                if (!$whiteList->isEmpty()) {
                    $richRoutings = $this->enrichRouting($whiteList);
                    $this->prodRoutingDatasource = $richRoutings;
                }
            }
        }
        return $this->prodRoutingDatasource;
    }

    public function getMatrixDataSource($xAxis)
    {
        if (isset($this->dashboardConfig["show_all_ics_by_sub_project_client_box"])) {
            return parent::getMatrixDataSource($xAxis);
        }
        if (is_null($this->matrixDataSourceSingleton)) {
            $nominatedFns = $this->getNominatedFns();
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
                    ->with($nominatedFns)
                    ->get();

                //Only filter sheets when it is not the sandbox sub project(s)
                if (in_array($subProjectId, [112])) {
                    foreach ($sheets as $sheet) {
                        $result[$key][] = $sheet;
                    }
                } else {
                    foreach ($sheets as $sheet) {
                        foreach ($nominatedFns as $fn) {
                            $extInsp = $sheet->{$fn};
                            if ($extInsp->contains($cuid)) {
                                $result[$key][] = $sheet;
                            }
                        }
                    }
                }
                $result[$key] = array_unique($result[$key]);
            }
            $this->matrixDataSourceSingleton = $result;
        }
        return $this->matrixDataSourceSingleton;
    }

    protected function getXAxis()
    {
        $xAxis = parent::getXAxis();
        if (isset($this->dashboardConfig["show_all_ics_by_sub_project_client_box"])) {
            return $xAxis;
        }
        $sheets = $this->getMatrixDataSource(null);
        foreach (array_keys($this->matrixes) as $key) {
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
        if (isset($this->dashboardConfig["show_all_ics_by_sub_project_client_box"])) {
            return $yAxis;
        }
        $sheets = $this->getMatrixDataSource(null);
        foreach (array_keys($this->matrixes) as $key) {
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

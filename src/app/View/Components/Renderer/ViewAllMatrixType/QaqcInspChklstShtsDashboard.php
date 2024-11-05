<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Http\Controllers\Workflow\LibDashboards;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShtsDashboard extends QaqcInspChklstShts
{
    private $dashboardConfig;
    protected $allowCreation = false;

    public function __construct()
    {
        $cu = CurrentUser::get();
        $this->dashboardConfig = LibDashboards::getAll()[$cu->discipline] ?? null;
        if (!$this->dashboardConfig) {
            abort(404, "Your discipline [#" . ($cu->discipline ?? "?") . "] is not supported for this dashboard - Manage Dashboards.");
        }
        parent::__construct();

        dump($this->dashboardConfig);

        $this->metaShowProgress = isset($this->dashboardConfig['show_column_progress']);
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
            if (isset($this->dashboardConfig['show_all_ics_by_sub_project_project_client_box'])) {
                $sub4 = $cu->getSubProjectsOfProjectClient;
            }

            $all = collect([...$sub1, ...$sub2, ...$sub3, ...$sub4,]);

            $this->subProjectDatasource = $all;
            // dump($all);
            // } else {
            //     echo "CACHE HIT - getSubProjectListForFilter";
        }
        return $this->subProjectDatasource;
    }
}

<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilterDataSource;

use App\Utils\Support\CurrentUser;

trait TraitFilterDataSourceForClient
{
    use TraitFilterDataSourceCommon;

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
        $result = $this->subProjectDatasource;
        // dump($result);

        return $result;
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
}

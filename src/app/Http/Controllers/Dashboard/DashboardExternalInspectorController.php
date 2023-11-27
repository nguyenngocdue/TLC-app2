<?php

namespace App\Http\Controllers\Dashboard;

use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardExternalInspectorController extends DashboardController
{
    public function index(Request $request)
    {
        $userSettings = $this->getUserSettings();
        // dump($userSettings);
        [$sub_project_id, $prod_routing_id, $qaqc_insp_tmpl_id] = $userSettings;
        $cu = CurrentUser::get();
        $subProjects = $cu->getSubProjectsOfExternalInspector();
        // dump($subProjects);
        $defaultSubProject = (sizeof($subProjects) == 1) ? $subProjects->first()->id : null;
        // dump($defaultSubProject);

        $qaqcInspTmpls = $cu->getQaqcInspTmplsOfExternalInspector();
        // dump($qaqcInspTmpls);
        $defaultQaqcInspTmpl = (sizeof($qaqcInspTmpls) == 1) ? $qaqcInspTmpls->first()->id : null;

        $prodRoutings = $cu->getProdRoutingsOfExternalInspector();
        // dump($prodRoutings);
        $defaultProdRouting = (sizeof($prodRoutings) == 1) ? $prodRoutings->first()->id : null;

        $params = [
            'viewportParams' => [
                'sub_project_id' => $sub_project_id ?: $defaultSubProject,
                'qaqc_insp_tmpl_id' => $qaqc_insp_tmpl_id ?: $defaultQaqcInspTmpl,
                'prod_routing_id' => $prod_routing_id ?: $defaultProdRouting,
            ],
            'dataSource' => [
                'sub_projects' => $subProjects,
                'qaqc_insp_tmpls' => $qaqcInspTmpls,
                'prod_routings' => $prodRoutings,
            ],
            'showOnlyInvolved' => true,
        ];

        return view('dashboards.dashboard-external-inspector', $params);
    }
}

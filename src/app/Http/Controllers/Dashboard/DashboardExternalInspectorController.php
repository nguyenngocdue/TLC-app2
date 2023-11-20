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

        $qaqcInspTmpls = $cu->getQaqcInspTmplsOfExternalInspector();
        // dump($qaqcInspTmpls);

        $prodRoutings = $cu->getProdRoutingsOfExternalInspector();
        // dump($prodRoutings);

        $params = [
            'viewportParams' => [
                'sub_project_id' => $sub_project_id,
                'qaqc_insp_tmpl_id' => $qaqc_insp_tmpl_id,
                'prod_routing_id' => $prod_routing_id,
            ],
            'dataSource' => [
                'sub_projects' => $subProjects,
                'qaqc_insp_tmpls' => $qaqcInspTmpls,
                'prod_routings' => $prodRoutings,
            ],
        ];

        return view('dashboards.dashboard-external-inspector', $params);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardExternalInspectorController extends DashboardController
{
    public function index(Request $request)
    {
        $cu = CurrentUser::get();
        $subProjects = $cu->getSubProjectsOfExternalInspector();
        // dump($subProjects);

        $qaqcInspTmpls = $cu->getQaqcInspTmplsOfExternalInspector();
        // dump($qaqcInspTmpls);

        $params = [
            'viewportParams' => [
                'sub_project_id' => 112,
                'qaqc_insp_tmpl_id' => 49,
                'prod_routing_id' => 49,
            ],
            'dataSource' => [
                'sub_projects' => $subProjects,
                'qaqc_insp_tmpls' => $qaqcInspTmpls,
            ],
        ];

        return view('dashboards.dashboard-external-inspector', $params);
    }
}

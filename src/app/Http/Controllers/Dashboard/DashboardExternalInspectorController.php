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

        $prodRoutings = $cu->getProdRoutingsOfExternalInspector();
        // dump($prodRoutings);

        $params = [
            'viewportParams' => [
                'sub_project_id' => 112,
                'qaqc_insp_tmpl_id' => 49,
                'prod_routing_id' => 49,
            ],
            'dataSource' => [
                'sub_projects' => $subProjects,
                'qaqc_insp_tmpls' => $qaqcInspTmpls,
                'prod_routings' => $prodRoutings,
            ],
        ];
        $visible = false;
        foreach ($params['dataSource'] as $tableName => $dataSource) {
            $value = sizeof($dataSource) < 2;
            $params['hidden'][$tableName] = $value;
            if (!$value) $visible = true;
        }
        $params['visible'] = $visible;
        // dump($params);

        return view('dashboards.dashboard-external-inspector', $params);
    }
}

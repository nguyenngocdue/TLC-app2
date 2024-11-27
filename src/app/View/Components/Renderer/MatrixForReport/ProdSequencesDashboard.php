<?php

namespace App\View\Components\Renderer\MatrixForReport;

use App\Http\Controllers\Workflow\LibDashboards;
use App\Http\Services\UpdateUserSetting2Service;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAllMatrixFilterDataSource\TraitFilterDataSourceForClient;
use Illuminate\Support\Facades\Log;

class ProdSequencesDashboard extends ProdSequences
{
    use TraitFilterDataSourceForClient;

    protected $project_id, $sub_project_id, $prod_routing_id, $prod_discipline_id;
    protected $projectDatasource, $subProjectDatasource, $prodRoutingDatasource;

    private $dashboardConfig;
    public $type = "prod_sequences";

    public function __construct()
    {
        $cu = CurrentUser::get();
        $this->dashboardConfig = LibDashboards::getAll()[$cu->discipline] ?? null;
        if (!$this->dashboardConfig) {
            abort(404, "Your discipline [#" . ($cu->discipline ?? "?") . "] is not supported for this dashboard - Manage Dashboards.");
        }
        parent::__construct();

        $settings = UpdateUserSetting2Service::getInstance();
        $this->project_id = $settings->get("prod_sequences.view_all.matrix.project_id");
        $this->sub_project_id = $settings->get("prod_sequences.view_all.matrix.sub_project_id");
        $this->prod_routing_id = $settings->get("prod_sequences.view_all.matrix.prod_routing_id");
    }

    protected function getFilterDataSource()
    {
        return [
            'projects' => $this->getProjectListForFilter(),
            'sub_projects' => $this->getSubProjectListForFilter(),
            'prod_routings' => $this->getRoutingListForFilter(),
        ];
    }

    protected  function getViewportParams()
    {
        $result =  [
            'project_id' => $this->project_id ?? 1,
            'sub_project_id' => $this->sub_project_id ?? 1,
            'prod_routing_id' => $this->prod_routing_id ?? 1,
            'prod_discipline_id' => $this->prod_discipline_id ?? 1,
        ];
        // dump($result);
        return $result;
    }

    function render()
    {
        $filterDataSource  = $this->getFilterDataSource();
        $viewportParams = $this->getViewportParams();

        return view("dashboards.prod-sequences.prod-sequences-dashboard", [
            "filterDataSource" => $filterDataSource,
            'viewportParams' => $viewportParams,
        ]);
    }
}

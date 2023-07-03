<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Qaqc_wir;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;

class ViewAllTypeMatrixTypeProjectSubprojectRouting extends ViewAllTypeMatrixParent
{
    use TraitViewAllFunctions;
    private $project, $subProject, $prodRouting;
    protected $dataIndexX = "wir_description_id";
    protected $dataIndexY = "prod_order_id";
    protected $yAxis = Prod_order::class;
    protected $xAxis = Wir_description::class;
    protected $rotate45Width = 400;
    protected $groupBy = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, $this->subProject, $this->prodRouting] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : 21;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : 2;
        // dump($this->project, $this->subProject, $this->prodRouting);
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing'] ?? null;
        return [$project, $subProject, $prodRouting];
    }

    protected function getXAxis()
    {
        $result = [];
        $data = Prod_routing::find($this->prodRouting)->getWirDescriptions();
        foreach ($data as $line) {
            $result[] = [
                'dataIndex' => $line->id,
                'title' => $line->name,
                'align' => 'center',
                'prod_discipline_id' => $line->prod_discipline_id,
                'def_assignee' => $line->def_assignee,
                'width' => 40,
            ];
        }
        usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    protected function getYAxis()
    {
        $data = ($this->yAxis)::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            ->orderBy('name')
            ->get();

        return $data;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
        ];
    }

    protected function getMatrixDataSource($xAxis)
    {
        $lines = Qaqc_wir::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            ->get();
        return $lines;
    }

    protected function getCreateNewParams($x, $y)
    {
        // dump($x);
        // dump($y);
        // dd();
        $params = parent::getCreateNewParams($x, $y);
        $params['project_id'] =  $this->project;
        $params['sub_project_id'] =  $this->subProject;
        $params['prod_routing_id'] =  $this->prodRouting;

        $params['prod_order_id'] =  $y->id;
        $params['prod_discipline_id'] =  $x['prod_discipline_id'];
        $params['assignee_1'] =  $x['def_assignee'];
        return $params;
    }
}

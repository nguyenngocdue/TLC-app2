<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Sub_project;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class __Template extends ViewAllTypeMatrixParent
{
    // use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "prod_routing_id";
    protected $yAxis = Sub_project::class;
    protected $dataIndexY = "sub_project_id";
    // protected $rotate45Width = 400;
    protected $tableTrueWidth = true;
    protected $headerTop = 20 * 16;
    protected $groupBy = null;
    // protected $mode = 'status';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, $this->subProject, $this->prodRouting, $this->prodRoutingLink, $this->prodDiscipline] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : null;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : null;
        $this->prodRoutingLink = $this->prodRoutingLink ? $this->prodRoutingLink : [];
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        $prodRoutingLink = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_link_id'] ?? null;
        $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
        return [$project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline];
    }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            // ->where('sub_project_id', $this->subProject)
            // ->where('prod_routing_id', $this->prodRouting)
            // ->with('getRoomType')
            ->orderBy('name')
            ->get();
        return $yAxis;
    }

    protected function getXAxisPrimaryColumns()
    {
        $data = Prod_routing::query();
        // if ($this->prodDiscipline) $data = $data->where('prod_discipline_id', $this->prodDiscipline);
        // if ($this->prodRoutingLink) $data = $data->whereIn('prod_routing_link_id', $this->prodRoutingLink);
        $data = $data->orderBy('name')->get();
        return $data;
    }

    protected function getXAxis()
    {
        $result = [];
        // if (is_null($this->subProject)) {
        //     echo Blade::render("<x-feedback.alert type='error' message='You must specify Sub-Project.'></x-feedback.alert>");
        //     return [];
        // }
        $data = $this->getXAxisPrimaryColumns();
        // dump($data);
        foreach ($data as $line) {
            $item = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name,
            ];
            $result[] = $item;
        }
        return $result;
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        // return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $lines = Prod_order::query()->get();
        // ->where('sub_project_id', $this->subProject)
        // ->where('prod_routing_id', $this->prodRouting);
        // if ($this->prodDiscipline) $lines = $lines->where('prod_discipline_id', $this->prodDiscipline);
        // if ($this->prodRoutingLink) $lines = $lines->whereIn('prod_routing_link_id', $this->prodRoutingLink);
        // dump($lines[0]);
        return $lines;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            'prod_routing_link_id' => $this->prodRoutingLink,
            'prod_discipline_id' => $this->prodDiscipline,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        // $params['project_id'] =  $this->project;
        $params['sub_project_id'] =  $this->subProject;
        $params['prod_routing_id'] =  $this->prodRouting;

        // $params['prod_discipline_id'] =  $x['prod_discipline_id'];
        return $params;
    }
}

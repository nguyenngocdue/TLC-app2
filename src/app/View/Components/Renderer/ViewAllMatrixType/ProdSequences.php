<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_discipline;
use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Str;

class ProdSequences extends ViewAllTypeMatrixParent
{
    // use TraitFilterMonth;

    private $project, $subProject, $prodRouting;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "prod_routing_link_id";
    protected $yAxis = Prod_order::class;
    protected $dataIndexY = "prod_order_id";
    protected $rotate45Width = 400;
    protected $tableTrueWidth = true;
    protected $headerTop = "[300px]";
    // protected $xAxis = Date::class;
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
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        return [$project, $subProject, $prodRouting];
    }

    protected function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            ->orderBy('name')
            ->get();
        return $yAxis;
    }

    private function getDisciplines()
    {
        $result = [];
        $disciplines = Prod_discipline::all();
        foreach ($disciplines as $discipline) {
            $result[$discipline->id] = $discipline;
        }
        return $result;
    }

    protected function getXAxis()
    {
        $result = [];
        $data = Prod_routing::find($this->prodRouting)->getProdRoutingLinks()->orderBy('order_no')->get();
        foreach ($data as $line) {
            $result[] = [
                'dataIndex' => $line->id,
                'title' => $line->name,
                'align' => 'center',
                'width' => 40,
                'prod_discipline_id' => $line->prod_discipline_id,
            ];
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        $disciplines = $this->getDisciplines();
        $icons = [
            1 => "<span class=''>PPR</span>",
            2 => "<span class=''>STR</span>",
            3 => "<span class=''>FIT</span>",
            4 => "<span class=''>MEPF</span>",
            5 => "<span class=''>WH</span>",
            6 => "<span class=''>SHP</span>",
            7 => "<span class=''>QAQC</span>",
        ];
        $bg = [
            1 => "bg-stone-400 text-stone-600",
            2 => "bg-slate-400 text-slate-600",
            3 => "bg-emerald-700 text-emerald-300",
            4 => "bg-indigo-300 text-indigo-700",
            5 => "bg-lime-300 text-lime-700",
            6 => "bg-orange-300 text-orange-700",
            7 => "bg-blue-400 text-blue-600",
        ];
        foreach ($xAxis as $line) {
            $discipline = $disciplines[$line['prod_discipline_id']];
            $result[$line['dataIndex']] = (object)[
                'value' => $icons[$discipline->id] ?? "",
                'cell_title' => $discipline->name,
                'cell_class' => $bg[$discipline->id] ?? "",
            ];
        }
        return $result;
    }

    protected function getMatrixDataSource($xAxis)
    {
        $lines = Prod_sequence::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting);
        // dump($lines);
        return $lines->get();
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        // $params['project_id'] =  $this->project;
        $params['sub_project_id'] =  $this->subProject;
        $params['prod_routing_id'] =  $this->prodRouting;
        return $params;
    }

    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'production_name',  'width' => 300,],
            ['dataIndex' => 'quantity', 'align' => 'center', 'width' => 50, 'align' => 'right'],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis)
    {
        return [
            'production_name' => $y->production_name,
            'quantity' => $y->quantity,
        ];
    }
}

<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Qaqc_wir;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Str;

class QaqcWirs extends ViewAllTypeMatrixParent
{
    use TraitViewAllFunctions;
    use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting, $prodDiscipline;
    protected $dataIndexX = "wir_description_id";
    protected $dataIndexY = "prod_order_id";
    protected $yAxis = Prod_order::class;
    protected $xAxis = Wir_description::class;
    protected $rotate45Width = 400;
    protected $groupBy = null;
    protected $tableTrueWidth = true;
    protected $headerTop = "310px";
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->project, $this->subProject, $this->prodRouting, $this->prodDiscipline] = $this->getUserSettings();
        $this->project = $this->project ? $this->project : 5;
        $this->subProject = $this->subProject ? $this->subProject : 21;
        $this->prodRouting = $this->prodRouting ? $this->prodRouting : 2;
        $this->prodDiscipline = $this->prodDiscipline ? $this->prodDiscipline : 2;
        // dump($this->project, $this->subProject, $this->prodRouting);
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
        $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
        $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
        $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
        return [$project, $subProject, $prodRouting, $prodDiscipline];
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
            'prod_discipline_id' => $this->prodDiscipline,
        ];
    }

    protected function getMatrixDataSource($xAxis)
    {
        $lines = Qaqc_wir::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            ->where('prod_discipline_id', $this->prodDiscipline)
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

    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'quantity',  'width' => 50, "fixed" => "left", 'align' => 'right'],
            ['dataIndex' => 'progress', 'width' => 50, "fixed" => "left", 'align' => 'right'],
            ['dataIndex' => 'production_name',  'width' => 300, "fixed1" => "left",],
            // ['dataIndex' => 'compliance_name',  'width' => 300, "fixed1" => "left",],
        ];
    }

    function getProgress($line)
    {
        $result = 0;
        foreach ($line as $cell) {
            foreach ($cell as $wir) {
                $result += in_array($wir->status, ['closed', 'not_applicable']);
            }
        }
        return $result;
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel)
    {
        $prodOrderId = $y->id;
        $line = $dataSource[$prodOrderId] ?? [];
        $wirCount = count($xAxis);
        $progress = ($wirCount) ? round(100 * $this->getProgress($line) / $wirCount, 2) : 0;
        return [
            'production_name' =>  $y->production_name,
            'compliance_name' =>  $y->compliance_name,
            'quantity' => $y->quantity,
            'progress' => $progress . "%",
        ];
    }
}

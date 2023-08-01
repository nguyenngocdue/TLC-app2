<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;
use App\Models\Term;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProdSequences extends ViewAllTypeMatrixParent
{
    // use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "prod_routing_link_id";
    protected $yAxis = Prod_order::class;
    protected $dataIndexY = "prod_order_id";
    // protected $rotate45Width = 400;
    protected $tableTrueWidth = true;
    protected $headerTop = "20";
    // protected $headerTop = "[300px]";
    protected $groupBy = null;
    protected $mode = 'detail';
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
        $this->cacheUnit();
    }

    private $unit;
    private function cacheUnit()
    {
        $result = [];
        $terms = Arr::groupBy(Term::all()->toArray(), 'id');
        foreach ($terms as $key => $subArr) {
            $result[$key]   = array_pop($subArr);
        }
        // dump($result);
        $this->unit = $result;
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

    protected function getXAxisExtraColumns()
    {
        return ["man_power", "uom", "total_uom", "total_mins", "min_per_uom"];
    }

    protected function getXAxis()
    {
        $result = [];
        $data = Prod_routing::find($this->prodRouting)->getProdRoutingLinks()->orderBy('order_no')->get();
        $extraColumns = $this->getXAxisExtraColumns();
        foreach ($data as $line) {
            $result[] = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name,
                'align' => 'center',
                'width' => 40,
                'prod_discipline_id' => $line->prod_discipline_id,
                "colspan" => 1 + sizeof($extraColumns),
            ];
            foreach ($extraColumns as $column) {
                $item = [
                    'dataIndex' => $line->id . "_" . $column,
                    'columnIndex' => $column,
                    'align' => 'center',
                    'width' => 40,
                    'isExtra' => true,
                ];
                // if ($column == 'min_per_uom') $item['title'] = "Min/UoM";
                $result[] = $item;
            }
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    protected function getMatrixDataSource($xAxis)
    {
        $lines = Prod_sequence::query()
            ->where('sub_project_id', $this->subProject)
            ->where('prod_routing_id', $this->prodRouting)
            // ->with('getUomId')
        ;
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

    function cellRenderer($cell, $dataIndex, $forExcel = false)
    {
        if ($dataIndex === 'status') return parent::cellRenderer($cell, $dataIndex, $forExcel);
        $doc = $cell[0];
        switch ($dataIndex) {
            case "man_power":
                return $doc->worker_number;
            case "total_uom":
                return $doc->total_uom . " "; //. ($this->unit[$doc->uom_id]['name'] ?? "(unit)");
            case "total_mins":
                return $doc->total_hours * 60;
            case "min_per_uom":
                return ($doc->total_uom > 0) ? round($doc->total_hours * 60 / $doc->total_uom, 2) : '<i class="fa-solid fa-infinity" title="DIV 0"></i>';
            case "uom":
                return $this->unit[$doc->uom_id]['name'] ?? "(unit)";
            default:
                return "852. Not found " . $dataIndex;
        }
    }
    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        foreach ($xAxis as $line) {
            $result[$line['dataIndex']] =  Str::headline($line['columnIndex']);
            if ($line['columnIndex'] == 'total_uom') $result[$line['dataIndex']] = "Total UoM";
            if ($line['columnIndex'] == 'min_per_uom') $result[$line['dataIndex']] = "min/UoM";
            if ($line['columnIndex'] == 'uom') $result[$line['dataIndex']] = "UoM";
            if ($line['columnIndex'] == 'total_mins') $result[$line['dataIndex']] = "min";
            if ($line['columnIndex'] == 'man_power') $result[$line['dataIndex']] = "m'p";
        }
        foreach ($result as &$row) {
            $row = "<div class='p-1 text-center'>" . $row . "</div>";
        }
        return $result;
    }
}

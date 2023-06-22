<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\Hr_timesheet_worker;
use App\Models\User_team_tsht;
use App\Utils\Constant;
use Illuminate\View\Component;

class ViewAllTypeMatrix extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $yAxis = User_team_tsht::class,
        private $viewportDate = null,
    ) {
        // dump($this->viewportDate);
        $this->viewportDate = strtotime($this->viewportDate ? $this->viewportDate : now());
        // dump($this->viewportDate);
    }

    function getYAxis()
    {
        $yAxis = $this->yAxis::orderBy('name')->get();
        return $yAxis;
    }

    function getXAxis()
    {
        $xAxis = [];
        $date0 = date(Constant::FORMAT_DATE_MYSQL, $this->viewportDate); //today date
        for ($i = -7; $i < 2; $i++) {
            $date = date(Constant::FORMAT_DATE_MYSQL, strtotime("+$i day", strtotime($date0)));
            $xAxis[] = date(Constant::FORMAT_DATE_MYSQL, strtotime($date));
        }
        // dump($xAxis);

        $xAxis = array_map(fn ($c) => [
            'dataIndex' => $c,
            'title' => date(Constant::FORMAT_DATE_ASIAN, strtotime($c)) . "<br>" . date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($c)),
            'column_class' => ("Sun" == date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($c))) ? "bg-pink-200" : (($c == date(Constant::FORMAT_DATE_MYSQL)) ? "bg-blue-200" : ""),
        ], $xAxis);
        return $xAxis;
    }

    function getDataSource($xAxis)
    {
        // dump($xAxis);
        $firstDay = $xAxis[0]['dataIndex'];
        $lastDay =  $xAxis[sizeof($xAxis) - 1]['dataIndex'];
        $lines = Hr_timesheet_worker::whereBetween('ts_date', [$firstDay, $lastDay])->get();
        return $lines;
    }

    function reIndexDataSource($dataSource, $groupX, $groupY)
    {
        $result = [];
        foreach ($dataSource as $line) {
            $result[$line->$groupY][$line->$groupX][] = $line;
        }
        return $result;
    }

    function cellRenderer($cell)
    {
        // return ($cell);
        $result = [];
        $statuses = LibStatuses::getFor($this->type);
        foreach ($cell as $document) {
            $status = $statuses[$document->status];
            // dump($statuses[$status]);
            $result[] = (object)[
                'value' => $document->id,
                'cell_title' => 'Open this document',
                $bgColor = "bg-" . $status['color'] . "-" . $status['color_index'],
                $textColor = "text-" . $status['color'] . "-" . (1000 - $status['color_index']),
                'cell_class' => "$bgColor $textColor",
                'cell_href' => route($this->type . ".edit", $document->id),
            ];
        }
        // dump($result);
        if (sizeof($result) == 1) return $result[0];
        return $result;
        // return [1, 2];
    }

    function mergeDataSource($xAxis, $yAxis, $dataSource)
    {
        $dataSource = $this->reIndexDataSource($dataSource, 'ts_date', 'team_id',);
        $result = [];
        $tableName = (new $this->yAxis)->getTableName();
        foreach ($yAxis as $y) {
            $yId = $y->id;
            $line['name_for_group_by'] = $y->name;

            $line['name'] = (object)[
                'value' => $y->name,
                'cell_title' => "(#" . $y->id . ")",
                'cell_class' => "text-blue-800",
                'cell_href' => route($tableName . ".edit", $y->id),
            ];
            $line['count'] = count($y->getTshtMembers());
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                $xClass = $x['column_class'];
                $line[$xId] = (object)[
                    'value' => '<i class="fa-duotone fa-circle-plus"></i>',
                    'cell_href' => route($this->type . ".create"),
                    'cell_class' => "text-center text-green-800 $xClass",
                    'cell_title' => "Create a new document",
                ];
            }
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                if (isset($dataSource[$yId][$xId])) {
                    $line[$xId] = $this->cellRenderer($dataSource[$yId][$xId]);
                }
            }
            $result[] = $line;
        }
        // dump($result);
        return $result;
    }

    private function getHrefArray()
    {
        $minus1year = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 year", $this->viewportDate));
        $minus1month = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 month", $this->viewportDate));
        $minus1week = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 week", $this->viewportDate));
        $today = date(Constant::FORMAT_DATE_MYSQL);
        $plus1week = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 week", $this->viewportDate));
        $plus1month = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 month", $this->viewportDate));
        $plus1year = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 year", $this->viewportDate));

        return [
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$minus1year",
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$minus1month",
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$minus1week",
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$today",
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$plus1week",
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$plus1month",
            "?action=updateViewAllMatrix&_entity={$this->type}&viewportDate=$plus1year",
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $xAxis = $this->getXAxis();
        // dump($xAxis);
        $yAxis = $this->getYAxis();
        $dataSource = $this->getDataSource($xAxis);
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $dataSource);
        $columns = [
            ['dataIndex' => 'name_for_group_by', 'hidden' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'count', 'align' => 'center'],
            ...$xAxis,
        ];
        return view(
            'components.renderer.view-all-type-matrix',
            [
                'columns' => $columns,
                'dataSource' => $dataSource,
                'type' => $this->type,
                'href' => $this->getHrefArray(),
            ],
        );
    }
}

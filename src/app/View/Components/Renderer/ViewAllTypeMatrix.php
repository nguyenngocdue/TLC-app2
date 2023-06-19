<?php

namespace App\View\Components\Renderer;

use App\Models\Hr_timesheet_worker;
use App\Models\User_team_ot;
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
    ) {
        //
    }

    function getYAxis()
    {
        $yAxis = User_team_ot::get();
        return $yAxis;
    }

    function getXAxis()
    {
        $xAxis = [];
        $date0 = date(Constant::FORMAT_DATE_MYSQL); //today date
        for ($i = -7; $i < 3; $i++) {
            $date = date(Constant::FORMAT_DATE_MYSQL, strtotime("+$i day", strtotime($date0)));
            $xAxis[] = date(Constant::FORMAT_DATE_MYSQL, strtotime($date));
        }
        // dump($xAxis);

        // $xAxis = ['2023-06-12', '2023-06-13', '2023-06-14', '2023-06-15', '2023-06-16', '2023-06-17', '2023-06-18',];
        $xAxis = array_map(fn ($c) => ['dataIndex' => $c, 'title' => date(Constant::FORMAT_DATE_ASIAN, strtotime($c))], $xAxis);
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
        foreach ($cell as $document) {
            $result[] = (object)[
                'value' => $document->id,
                // 'cell_title' => 'Open',
                'cell_class' => 'bg-yellow-300',
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
        foreach ($yAxis as $y) {
            $yId = $y->id;
            $line['name'] = $y->name;
            // $line['count'] = -11111111;
            $line['count'] = count($y->getOtMembers());
            foreach ($xAxis as $x) {
                $xId = $x['dataIndex'];
                $line[$xId] = (object)[
                    'value' =>    "Create New",
                    'cell_href' => route($this->type . ".create"),
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $xAxis = $this->getXAxis();
        $yAxis = $this->getYAxis();
        $dataSource = $this->getDataSource($xAxis);
        $dataSource = $this->mergeDataSource($xAxis, $yAxis, $dataSource);
        $columns = [
            ['dataIndex' => 'name'],
            ['dataIndex' => 'count', 'align' => 'center'],
            ...$xAxis,
        ];
        return view(
            'components.renderer.view-all-type-matrix',
            [
                'columns' => $columns,
                'dataSource' => $dataSource
            ],
        );
    }
}

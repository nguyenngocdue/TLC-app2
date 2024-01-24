<?php

namespace App\View\Components\Print;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class PrintCheckSheetPage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $layout = null,
        private $headerDataSource = null,
        private $tableColumns = null,
        private $tableDataSource = null,
        private $nominatedListFn = null,
    ) {
        //
        // dump("Say something");
    }

    private function getProgressDataSource($dataSource)
    {
        // dump($dataSource);
        $result = [];
        foreach ($dataSource as $checkpoint) {
            // dump();
            if (($checkpoint['control_type_id'] ?? '') == 4) {
                // dump($checkpoint);
                $value = $checkpoint['qaqc_insp_control_value_id'] ?? $checkpoint['hse_insp_control_value_id'] ?? 0;
                if (!isset($result[$value])) $result[$value] = 0;
                $result[$value]++;
            }
        }
        // dump($result);
        $all = array_sum($result);
        $yes = $result[1] ?? 0;
        $no = $result[2] ?? 0;
        $na = $result[3] ?? 0;
        $on_hold = $result[4] ?? 0;
        $progressData = [
            [
                'id' => 'yes',
                'color' => 'green',
                'percent' => (100 * $yes / $all) . '%',
                'label' => "Pass<br/>$yes/$all",
            ],
            [
                'id' => 'no',
                'color' => 'pink',
                'percent' => (100 * $no / $all) . '%',
                'label' => "Fail<br/>$no/$all",
            ],
            [
                'id' => 'na',
                'color' => 'gray',
                'percent' => (100 * $na / $all) . '%',
                'label' => "NA<br/>$na/$all",
            ],
            // [
            //     'id' => 'on_hold',
            //     'color' => 'orange',
            //     'percent' => (100 * $on_hold / $all) . '%',
            //     'label' => "On Hold<br/>$on_hold/$all",
            // ],
        ];

        return $progressData;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $isExternalInspector = CurrentUser::get()->isExternalInspector();
        if ($isExternalInspector) {
            $nominatedList = $this->headerDataSource->{$this->nominatedListFn . "_list"}()->pluck('id');
            if (!$nominatedList->contains(CurrentUser::id())) {
                return "<x-feedback.result type='warning' title='Permission Denied' message='You are not permitted to view this check sheet.<br/>If you believe this is a mistake, please contact our admin.' />";
            }
        }

        return view('components.print.print-check-sheet-page', [
            'type' => $this->type,
            'layout' => $this->layout,
            'headerDataSource' => $this->headerDataSource,
            'tableColumns' => $this->tableColumns,
            'tableDataSource' => $this->tableDataSource,
            'progressData' => $this->getProgressDataSource($this->tableDataSource),
        ]);
    }
}

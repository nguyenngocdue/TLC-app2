<?php

namespace App\View\Components\Renderer\MatrixForReport;

use Illuminate\Support\Facades\Log;

trait TraitMatrixForReportProgress
{
    function qaqc_wirs($xAxis, $yAxis, $dataSource)
    {
        $weightArray = $this->getWeightArray($xAxis, $yAxis, $dataSource);

        $result = [];
        // dump($dataSource);
        // dump(array_pop($dataSource));
        foreach ($dataSource as $id => $line) {
            $wa = $this->removeWeightOfNA($weightArray, $line);
            $totalWa = array_sum($wa);
            // dump($wa);
            $result[$id]['progress'] = 0;
            // dump($line);
            foreach ($line as $cell) {
                if (in_array($cell->status, $this->finishedArray)) {
                    $value = $wa[$cell->{$this->dataIndexX}] ?? 0;
                    $result[$id]['progress'] += 100 * $value / $totalWa;
                }
            }
        }

        // dump($dataSource);
        foreach ($dataSource as $id => &$line) {
            $line['progress'] = (object)[
                'value' => number_format($result[$id]['progress'], 2) . '%',
                'cell_class' => 'text-right',
            ];
        }
        return $dataSource;
    }

    function prod_sequences($xAxis, $yAxis, $dataSource)
    {
        $result = [];
        foreach ($dataSource as $id => $line) {
            $result[$id]['progress'] = 0;
            foreach ($line as $cell) {
                if (in_array($cell->status, $this->finishedArray)) {
                    $result[$id]['progress'] += 1;
                }
            }
        }

        foreach ($dataSource as $id => &$line) {
            $line['progress'] = (object)[

                'value' => '69%',
                'cell_class' => 'text-right',
            ];
        }
        return $dataSource;
    }

    function calculateProgressForRows($xAxis, $yAxis, $dataSource)
    {
        switch ($this->type) {
            case 'qaqc_wirs':
                return $this->qaqc_wirs($xAxis, $yAxis, $dataSource);
            case 'prod_sequences':
                return $this->prod_sequences($xAxis, $yAxis, $dataSource);
            default:
                Log::info("There is no custom progress function for this type: " . $this->type);
                return $dataSource;
        }
    }
}

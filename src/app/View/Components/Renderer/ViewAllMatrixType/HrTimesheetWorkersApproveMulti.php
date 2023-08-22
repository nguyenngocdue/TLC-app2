<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

class HrTimesheetWorkersApproveMulti extends HrTimesheetWorkers
{
    protected $mode = 'checkbox';

    protected $actionBtnShowExportCsv = false;
    protected $actionBtnShowPrintButton = true;

    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        return $result;
    }

    protected function getXAxis()
    {
        $result = [];
        $data = $this->getXAxisPrimaryColumns();
        foreach ($data as $line) {
            $result[] = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name,
                'align' => 'center',
                'width' => 40,
                'prod_discipline_id' => $line->prod_discipline_id,
            ];
        }
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }
    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'production_name',  'width' => 300, 'fixed' => 'left',],
        ];
    }
}

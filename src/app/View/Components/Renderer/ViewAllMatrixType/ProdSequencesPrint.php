<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\View\Components\Renderer\ViewAllMatrixType\ProdSequences;
use Illuminate\Support\Str;

class ProdSequencesPrint extends ProdSequences
{
    protected $mode = 'checkbox';
    protected $showPrintButton = true;

    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        // foreach ($xAxis as $line) {
        //     $result[$line['dataIndex']] =  $line->;
        // }

        // foreach ($result as &$row) {
        //     $row = "<div class='p-1 text-center'>" . $row . "</div>";
        // }
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

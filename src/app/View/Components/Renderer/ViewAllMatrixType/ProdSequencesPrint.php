<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_order;
use App\Models\Prod_routing;
use App\Models\Prod_sequence;
use App\Models\Term;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\View\Components\Renderer\ViewAllMatrixType\ProdSequences;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProdSequencesPrint extends ProdSequences
{
    protected $mode = 'checkbox';
    protected $showPrintButton = true;

    protected function getXAxisExtraColumns()
    {
        return [];
    }

    protected function getXAxis()
    {
        $result = [];
        $data = $this->getXAxisPrevious();
        // dump($data[0]);
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
    // protected function renderCellHasData(&$line,$y,$yAxisTableName){
    //     return $line['name'] = (object)[
    //         'value' => $y->name,
    //         'cell_title' => "(#" . $y->id . ")",
    //         'cell_class' => "text-blue-800 bg-white",
    //         'cell_href' => route($yAxisTableName . ".edit", $y->id),
    //     ];
    // }
}

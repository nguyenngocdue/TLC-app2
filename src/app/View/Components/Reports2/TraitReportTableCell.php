<?php

namespace App\View\Components\Reports2;


trait TraitReportTableCell
{
    public function makeRowValue($col){
        return [
            'cell_class' => $col['cell_class'] ?? null,
            'cell_div_class' => $col['cell_div_class'] ?? null,
            'cell_href' => $col['cell_href'] ?? null,
            'cell_title'=> $col['cell_title'] ?? null,
            'row_cell_div_class' => $col['row_cell_div_class'] ?? null,
            'row_cell_class' => $col['row_cell_class'] ?? null,
        ];
    }
    public function makeCellValue($values,$originalValue, $content, $cellClass = '',$href ='', $cellDivClass='', $cellTitle='') {
        $values = (object)[
            'original_value' => $originalValue, // to export excel
            'value' => $content,
            'cell_class' => $cellClass,
            'cell_div_class' =>  $cellDivClass,
            'cell_href' => $href,
            'cell_title' => $cellTitle,
        ];
        return $values;
    }

    // to initialize $dataIndex in $Values
    public function setCellValue(&$values, $dataIndex, $originalValue, $content, $cellClass='', $cellDivClass='', $href = '', $cellTitle='') {
        $values[$dataIndex] = $this->makeCellValue([],$originalValue, $content,$cellClass, $href, $cellDivClass, $cellTitle);
    }

}

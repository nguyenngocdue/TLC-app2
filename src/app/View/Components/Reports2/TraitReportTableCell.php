<?php

namespace App\View\Components\Reports2;


trait TraitReportTableCell
{
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

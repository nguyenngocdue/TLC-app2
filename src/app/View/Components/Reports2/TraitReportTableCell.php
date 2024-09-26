<?php

namespace App\View\Components\Reports2;


trait TraitReportTableCell
{
    public function makeCellValue($values,$originalValue, $content, $cellClass = '', $href ='') {
        $values = (object)[
            'original_value' => $originalValue, // to export excel
            'value' => $content,
            'cell_class' => $cellClass,
            'cell_href' => $href,
        ];
        return $values;
    }

    // to initialize $dataIndex in $Values
    public function setCellValue(&$values, $dataIndex, $content, $cellClass) {
        $values[$dataIndex] = $this->makeCellValue([],$content, $content,$cellClass);
    }

}

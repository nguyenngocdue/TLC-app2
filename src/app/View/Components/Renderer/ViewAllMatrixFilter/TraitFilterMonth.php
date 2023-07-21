<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Utils\Constant;

trait TraitFilterMonth
{
    protected function getFilterDataSource()
    {
        $minus1yearTimestamp = strtotime("-1 year", $this->viewportDate);
        $minus1year = date(Constant::FORMAT_DATE_MYSQL, $minus1yearTimestamp);
        $selectedYearTimestamp = strtotime("+0 year", $this->viewportDate);
        $selectedYear = date(Constant::FORMAT_DATE_MYSQL, $selectedYearTimestamp);
        $today = date(Constant::FORMAT_DATE_MYSQL);
        $plus1yearTimestamp = strtotime("+1 year", $this->viewportDate);
        $plus1year = date(Constant::FORMAT_DATE_MYSQL, $plus1yearTimestamp);

        return [
            '-1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1year",
            'today' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$today",
            'selectedYear' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$selectedYear",
            '+1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1year",

            '-1yearLabel' => "<- " . date(Constant::FORMAT_YEAR, $minus1yearTimestamp),
            'todayLabel' => "Reset to " . date(Constant::FORMAT_YEAR),
            'selectedYearLabel' =>  "Selected Year " . date(Constant::FORMAT_YEAR, $selectedYearTimestamp),
            '+1yearLabel' => date(Constant::FORMAT_YEAR, $plus1yearTimestamp) . "->",
        ];
    }
}

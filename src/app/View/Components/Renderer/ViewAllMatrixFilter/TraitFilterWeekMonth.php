<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilter;

use App\Utils\Constant;

trait TraitFilterWeekMonth
{
    protected function getFilterDataSource()
    {
        $minus1year = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 year", $this->viewportDate));
        $minus1month = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 month", $this->viewportDate));
        $minus1week = date(Constant::FORMAT_DATE_MYSQL, strtotime("-1 week", $this->viewportDate));
        $today = date(Constant::FORMAT_DATE_MYSQL);
        $plus1week = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 week", $this->viewportDate));
        $plus1month = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 month", $this->viewportDate));
        $plus1year = date(Constant::FORMAT_DATE_MYSQL, strtotime("+1 year", $this->viewportDate));

        return [
            '-1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1year",
            '-1month' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1month",
            '-1week' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$minus1week",
            'today' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$today",
            '+1week' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1week",
            '+1month' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1month",
            '+1year' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_date=$plus1year",

            'weekView' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_mode=week",
            'monthView' => "?action=updateViewAllMatrix&_entity={$this->type}&viewport_mode=month",
        ];
    }
}

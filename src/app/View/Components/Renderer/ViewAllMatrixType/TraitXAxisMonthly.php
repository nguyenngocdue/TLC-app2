<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Utils\Constant;

trait TraitXAxisMonthly
{
    protected function getXAxis()
    {
        $selectedYear = date('Y', $this->viewportDate);
        $xAxis = [];
        for ($i = 01; $i <= 12; $i++) {
            $xAxis[] = sprintf("$selectedYear-%02d-01", $i);
        }
        // dump($xAxis);

        $xAxis = array_map(fn ($c) => [
            'dataIndex' => substr($c, 0, 7),
            'title' => date(Constant::FORMAT_MONTH, strtotime($c)),
            'width' => 10,
            'align' => 'center',
        ], $xAxis);
        return $xAxis;
    }
}

<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use Carbon\Carbon;

trait TraitXAxisDate
{
    private function getBeginEndFromViewMode($date)
    {
        switch ($this->viewportMode) {
            case 'month':
                [$begin, $end] = DateTimeConcern::getMonthBeginAndEndDate0($date);
                $begin = Carbon::createFromDate($begin)->diffInDays($date);
                $end = Carbon::createFromDate($end)->diffInDays($date);
                $result = [-$begin, $end + 1];
                return $result;
            case 'week':
            default:
                $dayOfWeek = Carbon::createFromDate($date)->dayOfWeek  - 1;
                return [-$dayOfWeek, 7 - $dayOfWeek];
                // return [-7, 1];
        }
    }

    private function getColumnTitleFromViewMode($c)
    {
        switch ($this->viewportMode) {
            case 'month':
                return date('d', strtotime($c)) . "<br/>" . date('m', strtotime($c)) . "<br/>" . date('y', strtotime($c));
            case 'week':
            default:
                return date(Constant::FORMAT_DATE_ASIAN, strtotime($c)) . "<br>" . date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($c));
        }
    }

    protected function getXAxis()
    {
        $xAxis = [];
        $date0 = date(Constant::FORMAT_DATE_MYSQL, $this->viewportDate); //today date
        [$begin, $end] = $this->getBeginEndFromViewMode($date0);
        for ($i = $begin; $i < $end; $i++) {
            //Remove +$i as it cause date wrong
            $date = date(Constant::FORMAT_DATE_MYSQL, strtotime("$i day", strtotime($date0)));
            $xAxis[] = date(Constant::FORMAT_DATE_MYSQL, strtotime($date));
        }
        // dump($xAxis);

        $xAxis = array_map(fn ($c) => [
            'dataIndex' => $c,
            'title' => $this->getColumnTitleFromViewMode($c),
            'column_class' => ("Sun" == date(Constant::FORMAT_WEEKDAY_SHORT, strtotime($c))) ? "bg-gray-300" : (($c == date(Constant::FORMAT_DATE_MYSQL)) ? "bg-red-200 animate-pulse animate-bounce1" : ""),
            'width' => 10,
            'align' => 'center',
        ], $xAxis);

        // dump($xAxis);
        return $xAxis;
    }
}

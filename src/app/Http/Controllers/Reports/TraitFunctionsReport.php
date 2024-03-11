<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

trait TraitFunctionsReport
{
    function getDataSourceFromSqlStr($sql)
    {
        $sqlData = DB::select($sql);
        return $sqlData;
    }

    protected function makeColumns($dataSource, $params)
    {
        $columns = $this->getTableColumns($params, $dataSource);
        $columnKeys = array_column($columns, 'dataIndex');
        $columnNames =  array_map(function ($item) {

            if (!isset($item['title'])) return Report::makeTitle($item['dataIndex']);
            if (str_contains($item['title'], '/>')) {
                $str = trim(substr($item['title'], 0, strpos($item['title'], '<', 0)));
                return $str;
            }
            return $item['title'];
        }, $columns);
        return [$columnKeys, $columnNames];
    }

    private static function isCorrectDateFormat($dateString)
    {
        if (strlen($dateString) !== 8) return false;
        $day = substr($dateString, 0, 2);
        $month = substr($dateString, 3, 2);
        $year = substr($dateString, 6, 2);
        if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year)) return false;
        $day = intval($day);
        $month = intval($month);
        $year = intval($year);
        if ($day < 1 || $day > 31 || $month < 1 || $month > 12) return false;
        $currentYear = intval(date('y'));
        if ($year < $currentYear - 1 || $year > $currentYear + 1) return false;
        return true;
    }

    protected function makeColumnsPivotTable($dataSource, $params, $tableColumns)
    {
        $columns = $tableColumns;
        $columnKeys = array_column($columns, 'dataIndex');
        $columnNames =  array_map(function ($item) {
            $thirdUnderlined = PivotReport::findPosition($item['dataIndex'], '_', 3);
            $str = substr($item['dataIndex'], 0, $thirdUnderlined - 1);
            if (self::isCorrectDateFormat($str)) {
                return str_replace('_', '/', $str);
            } else {
                if (!isset($item['title'])) return Report::makeTitle($item['dataIndex']);
                return $item['title'];
            }
        }, $columns);
        return [$columnKeys, $columnNames];
    }


    protected function makeRowsFollowColumns($dataSource, $columnKeys)
    {
        // dd($dataSource, $columnKeys);
        try {
            $rows = [];
            $columnKeys = array_combine($columnKeys, $columnKeys);
            foreach ($dataSource as $key => $value) {
                $rows[] = array_intersect_key((array)$value, $columnKeys);
            }
            return $rows;
        } catch (\PDOException $e) {
            $e->getMessage();
        }
    }
}

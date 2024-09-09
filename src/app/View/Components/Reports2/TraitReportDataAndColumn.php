<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

trait TraitReportDataAndColumn
{
    use TraitCreateSQLReport2;
    use TraitReportTermNames;

    public function createIconPosition($content, $icon, $iconPosition)
    {
        $rowIconPosition = $this->getIconName($iconPosition);
        if ($rowIconPosition) {
            switch ($rowIconPosition) {
                case 'Right':
                    return $content . ' ' . $icon;
                case 'Left':
                default:
                    return $icon . ' ' . $content;
            }
        }
        return $content;
    }

    private function createMatrix($data, $column, $row, $cellValue, $rowTitle, $valueToSet=NUll) {
        $fields = [];
        foreach ($data as $key => $record) {
            if (Report::checkValueOfField((array)$record, $column)) {
                $field = $record->{$column};
                if (!in_array($field, $fields)) $fields[] = $field;
                $record->{$field} = $record->{$cellValue};
            }
            $record = (array)$record;
            $arr =  array_diff(array_keys($record), array_values($fields));
            $arr = array_diff($arr, [$row]);
            $flipped_array2 = array_flip($arr);
            $result = array_diff_key($record, $flipped_array2);
            if ($rowTitle){
                $result[$rowTitle] = $result[$row];
                unset($result[$row]);
            }
            $data[$key] = (object) $result;
        }
        $row = $rowTitle ? $rowTitle: $row; 
        $groupedByRow = Report::groupArrayByKey($data, $row);
        $mergedData = array_map(fn ($item) => array_merge(...$item), $groupedByRow);
        array_walk($mergedData, function(&$value) use($fields, $valueToSet) {
            $arrayDiff = array_diff(array_values($fields), array_keys($value));
            $arrayFill =  array_fill_keys($arrayDiff, $valueToSet);
            $value =  array_merge($value, $arrayFill);
        });
        // dd($mergedData);
        return array_values($mergedData);
    }

    private function transformData($dataSource, $transformedOption){
        $transformedOption = json_decode($transformedOption, true);
        uasort($transformedOption, function($a, $b) {return $a['order_no'] <=> $b['order_no'];});
        foreach($transformedOption as $type => $item) {
            switch ($type) {
                case 'grouping_to_matrix':
                    $params = $item['params'];
                    $column = $params['columns'];
                    $row = $params['row'];
                    $cellValue = $params['cell_value'];
                    $valueToSet = $params['empty_value'];
                    $rowTitle = $params['row_title'];
                    $transformedData = $this->createMatrix($dataSource, $column, $row, $cellValue, $rowTitle, $valueToSet);
                    return collect($transformedData);
                default:
                    dd('unknown type');
            }
        }
        // dd($transformedOption);
        return $dataSource;

    }

    public function getDataSQLString($block, $params)
    {
        $sqlString = $block->sql_string;
        if ($sqlString) {
            $sql = $this->getSql($sqlString, $params);
            // if (is_null($sql)) return collect();
            // if (!$sql) return collect();
            $sqlData = DB::select($sql);
            $collection = collect($sqlData);

            if ($block->is_transformed_data){
                $transformedOption = $block->transformed_data_json;
                $collection = $this->transformData($collection, $transformedOption);
            }
            // dd($collection);
            return $collection;
        }
        return collect();
    }

    public function getAllUniqueFields($collection)
    {
        return array_unique(array_keys((array)$collection->first()));
    }


    private function getSecondColumns($block)
    {
        $dataHeader = [];
        $secHeaderLines = $block->get2ndHeaderLines; #()->getParent();
        foreach ($secHeaderLines as $column) {
            // dd($column);
            $parent  = $column->getParent;
            if ($parent->is_active) {
                $content = $this->createIconPosition($column->name, $column->icon, $column->icon_position);
                $dataHeader[$parent->data_index] = (object)[
                    'value' => $content,
                    'cell_class' => $column?->cell_class,
                    'cell_div_class' =>  $column?->cell_div_class,

                ];
            }
        }
        return $dataHeader;
    }

    public function getDataColumnsFromDataSource($queriedData){

        $firstItem = $queriedData->first();
        $columns = [];
        if ($firstItem) {
            foreach(array_keys($firstItem) as $key) {
                $columns[] = [
                    'dataIndex' => $key,
                    'align' => 'center'
                ];
            }
        }
        return $columns;
    }

    public function getDataColumns($block, $queriedData)
    {
        if($queriedData->isEmpty()){
            $columnInstance = ReportColumn::getInstance($block);
            $headerCols = $columnInstance->defaultColumnsOnEmptyQuery($block);
            return [$headerCols, []];
        } 
        if ($block->is_transformed_data){
            return [$this->getDataColumnsFromDataSource($queriedData), []];
        }

        $uniqueFields = $this->getAllUniqueFields($queriedData);
        // config from admin
        $lines = $block->getLines->sortby('order_no');
        $secondHeaderCols = $this->getSecondColumns($block);
        $headerCols = [];
        foreach ($lines as $line) {
            $dataIndex = $line->data_index;
            $isActive = $line->is_active;
            if ($isActive && in_array($dataIndex, $uniqueFields)) {
                $aggFooter = $this->getAggName($line->agg_footer);
                $title = $this->createIconPosition($line->title ?? $line->name, $line->icon, $line->icon_position);
                $headerCols[] = [
                    'title' => $title,
                    'dataIndex' => $dataIndex,
                    'width' => $line->width,
                    'colspan' => $line->col_span,
                    'footer' => $aggFooter,
                ];
            }
        }
        return [$headerCols, $secondHeaderCols];
    }
}

<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

trait TraitReportDataAndColumn
{
    use TraitCreateSQLReport2;
    use TraitReportTermNames;
    use TraitReportMatrixColumn;

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


    private function transformData($dataSource, $transformedOption){
        $transformedOption = json_decode($transformedOption, true);
        uasort($transformedOption, function($a, $b) {return $a['order_no'] <=> $b['order_no'];});
        foreach($transformedOption as $type => $item) {
            switch ($type) {
                case 'grouping_to_matrix':
                    $params = $item['params'];
                    $hasTotalCols = $item['has_total_columns'] ?? null;
                    $transformedData = $this->createMatrix($dataSource, $params, $hasTotalCols);
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
                $transformedOption = $block->transformed_data_string;
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

    public function getDataColumnsFromDataSource($queriedData, $fields){

        $firstItem = $queriedData->first();
        $columns = [];
        if ($firstItem) {
            foreach(array_keys($firstItem) as $key) {
                if (in_array($key, $fields)) continue;
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


        $uniqueFields = $this->getAllUniqueFields($queriedData);
        // config from admin
        $columns = $block->getLines->sortby('order_no');
        $secondHeaderCols = $this->getSecondColumns($block);
        $headerCols = [];

        $fields = [];

        foreach ($columns as $line) {
            $dataIndex = $line->data_index;
            $isActive = $line->is_active;
            $fields[] = $dataIndex;
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
        if ($block->is_transformed_data){
            $transformedCols = $this->getDataColumnsFromDataSource($queriedData, $fields);
            $headerCols = array_merge($headerCols, $transformedCols);
        }
        return [$headerCols, $secondHeaderCols];
    }
}

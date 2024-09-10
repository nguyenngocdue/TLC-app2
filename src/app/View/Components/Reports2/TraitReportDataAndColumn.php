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
    use TraitReportTransformedData;

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

    public function getDataSQLString($block, $params)
    {
        $sqlString = $block->sql_string;
        if ($sqlString) {
            $sql = $this->getSql($sqlString, $params);
            // if (is_null($sql)) return collect();
            // if (!$sql) return collect();
            $sqlData = DB::select($sql);
            $queriedData = collect($sqlData);
            $fieldTransformation = [];
            if ($block->is_transformed_data){
                [$queriedData , $fieldTransformation]  = $this->transformData($queriedData, $block->transformed_data_string);
            }
            return [ $queriedData, $fieldTransformation ];
        }
        return [collect(), []];
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

    public function getDataColumnsByTransformData($queriedData, $fields, $transformedOpt){
        $firstItem = $queriedData->first();
        $columns = [];
        if ($firstItem) {
            $lastTransformedData = last($transformedOpt);
            if($lastTransformedData && isset($lastTransformedData['footer_agg'])){
                $footerAgg = $lastTransformedData['footer_agg'];
            }
            foreach(array_keys($firstItem) as $key) {
                if (in_array($key, $fields)) continue;
                $columns[] = [
                    'dataIndex' => $key,
                    'align' => 'center',
                    'footer' => $footerAgg ?? '',
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
        if ($block->is_transformed_data && ($x = $block->transformed_data_string)){
            $transformedOpt = $this->sortData($x, true);
            $transformedCols = $this->getDataColumnsByTransformData($queriedData, $fields, $transformedOpt);
            $headerCols = array_merge($headerCols, $transformedCols);
        }
        return [$headerCols, $secondHeaderCols];
    }
}

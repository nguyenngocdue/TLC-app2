<?php

namespace App\View\Components\Reports2;

use App\Utils\Support\Report;

class ReportTableColumn
{
    use TraitReportTermNames;
    use TraitReportTableContent;
    use TraitReportTransformedData;

    private static $instance = null;
    private function _construct(){

    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ReportTableColumn();
        }
        return self::$instance;
    }
    
    private function getAllUniqueFields($collection)
    {
        return array_unique(array_keys((array)$collection->first()));
    }

    function defaultColumnsOnEmptyQuery($block)
    {
        $cols = $block->getLines->where('is_active', true)
        ->select('title', 'data_index')
        ->map(function ($item) {
            return [
                'title' => $item['title'] ?? $item['data_index'],
                'dataIndex' => $item['data_index']
            ];
        })
        ->toArray();
        return $cols;
    }

    public function getColData($block, $queriedData, $transformedFields)
    {
        if($queriedData->isEmpty()){
            $columnInstance = ReportTableColumn::getInstance($block);
            $headerCols = $columnInstance->defaultColumnsOnEmptyQuery($block);
            return [$headerCols, []];
        } 
        $uniqueFields = $this->getAllUniqueFields($queriedData);
        // config from admin
        $columns = $block->getLines->sortby('order_no');
        $secondHeaderCols = $this->get2ndCols($block);
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
                    'fixed' => strtolower($this->getTermName($line->fixed)) ?? null,
                    'footer' => $aggFooter,
                ];
            }
           
        }
        if ($block->is_transformed_data && ($x = $block->transformed_data_string)){
            $transformedOpt = $this->sortData($x, true);
            $transformedCols = $this->getTransformedDataCols($queriedData, $fields, $transformedOpt , $transformedFields);
            $headerCols = array_merge($headerCols, $transformedCols);
        }
        // dd($headerCols);
        return [$headerCols, $secondHeaderCols];
    }

    private function get2ndCols($block)
    {
        $data2ndHeader = [];
        $headerLines = $block->get2ndHeaderLines; #()->getParent();
        foreach ($headerLines as $column) {
            // dd($column);
            $parent  = $column->getParent;
            if ($parent->is_active) {
                $content = $this->createIconPosition($column->name, $column->icon, $column->icon_position);
                $data2ndHeader[$parent->data_index] = (object)[
                    'value' => $content,
                    'cell_class' => $column?->cell_class,
                    'cell_div_class' =>  $column?->cell_div_class,
                    
                ];
                // $this->setCellValue($data2ndHeader,$parent->data_index, $content, $column?->cell_class, $column?->cell_div_class );
            }
        }
        // dd($data2ndHeader);
        return $data2ndHeader;
    }

    public function getTransformedDataCols($queriedData, $fields, $transformedOpt, $transformedFields){        
        $firstItem = $transformedFields;
        $columns = [];
        if ($firstItem) {
            $lastTransformedData = last($transformedOpt);
            $customCols = $lastTransformedData['custom_columns'] ?? [];
            $customColFields = array_map(fn($item) => $item['data_index'], $customCols);
            foreach($firstItem as $key) {
                if (in_array($key, $fields) || in_array($key, $customColFields)) continue;
                $columns[] = [
                    'dataIndex' => $key,
                    'align' => $lastTransformedData['align'] ?? 'center',
                    'footer' => $lastTransformedData['footer'] ?? '',
                    'width' => $lastTransformedData['width'] ?? 100,
                ];
            }
            if ($customCols) {
                foreach ($customCols as $value) {
                    if (in_array($value['data_index'], $fields) && !$value['display']) continue;
                    $newColumn = [
                        'title' => $value['title'] ?? $value['data_index'],
                        'dataIndex' => $value['data_index'],
                        'align' => $value['align'] ?? 'center',
                        'footer' => $value['footer'] ?? '',
                        'width' => $value['width'] ?? 100,
                    ];
                    // set position columns
                    $position = $value['position'] ?? count($columns);
                    $position = is_numeric($position) ?  $position : ($position == 'end' ? count($columns) : 0 );
                    array_splice($columns, $position, 1, [$newColumn]);
                }
            }
        }
        // dd($columns);
        return $columns;
    }

    public function createColsWhenNotFoundRenderType($queriedData)
    {
        $defaultCols = [];
        $firstRow = $queriedData->first();
        $keys = is_array($firstRow) ? array_keys($firstRow) : array_keys((array) $firstRow);
        if ($keys) $defaultCols = array_map(fn($item) => ['dataIndex' => $item,], $keys);
        return $defaultCols;
    }

    public function getConfiguredCols($columns, $dataIndexToRender = [])
    {
        $result = [];
        foreach ($columns as  $column) {
            if(!$column->is_active) continue;
            if (in_array($column->data_index, $dataIndexToRender)) {
                $result[$column->data_index] = $column;
            }
        }
        return $result;
    }

    public function updatedConfiguredCols($headerCols){
        $columns = [];
        foreach ($headerCols as $value) {
            $columns[$value['dataIndex']] = [
                "name" =>  $value['title'] ?? $value['dataIndex'],
            ];
        }
        return $columns;
    }


}

<?php

namespace App\View\Components\Reports2;

use Illuminate\Support\Facades\DB;

class ReportTableColumn
{
    use TraitReportTermNames;
    use TraitReportTableContent;
    use TraitReportTransformedData;
    use TraitReportDetectVariableChanges;


    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ReportTableColumn();
        }
        return self::$instance;
    }

    private function getAllUniqueFields($collection)
    {
        return array_unique(array_keys((array)$collection->first()));
    }

    private function defaultColumnsOnEmptyQuery($block,$currentParams)
    {
        // $title = $this->createHeaderTitle($line->title ?? $line->name, $line->icon, $line->icon_position, $currentParams);
        $cols = $block->getLines->where('is_active', true)
        ->select('title', 'data_index')
            ->map(function ($item) use($currentParams) {
                $title = $item['title'] ?? $item['data_index'];
                $title = $this->formatReportHref($title, $currentParams);
                return [
                    'title' => $title,
                    'dataIndex' => $item['data_index']
                ];
            })
            ->toArray();
        return $cols;
    }

    private function  makeDataByConfig($secondHeaderConfig, $currentParams){
        $sqlStr = $secondHeaderConfig['sql_check'];
        $sqlStr = $this->detectVariablesNoBlock($sqlStr, $currentParams);
        $data = collect(DB::select($sqlStr));
        return $data;
    }



    private function create2ndHeaderCols($headerCols, $block, $currentParams) {
        $configs = $block->transformed_data_string;
        $transformedOpt = json_decode($configs, true);
        if (isset($transformedOpt['grouping_to_matrix'])) {
            $groupingToMatrix = $transformedOpt['grouping_to_matrix'];
            if (isset($groupingToMatrix['second_header_col'])) {

                $secondHeaderConfig = $groupingToMatrix['second_header_col'];
                $fieldToGetVal = $secondHeaderConfig['field_get'];
                $fieldToCheckVal = $secondHeaderConfig['field_check'];
                $cssClassField = $secondHeaderConfig['css_class'] ?? '';
                
                $data = $this->makeDataByConfig($secondHeaderConfig, $currentParams);

                $xAxis2ndHeading = $data->pluck($fieldToGetVal, $fieldToCheckVal);
                $cssData = $data->pluck($cssClassField, $fieldToCheckVal);
                
                return collect($headerCols)->mapWithKeys(function ($col) use ($xAxis2ndHeading, $cssData) {
                    $dataIndex = $col['dataIndex'];
                    $cssClass = $cssData[$col['dataIndex']]?? null;
                    return [$dataIndex => (object) [
                        'value' =>$xAxis2ndHeading->get($dataIndex, ''),
                        'cell_class' => $cssClass,
                    ]];
                })->toArray();
            }  
        }
        return [];
    }


    public function getColData($block, $queriedData, $transformedFields, $currentParams)
    {
        if ($queriedData->isEmpty()) {
            $columnInstance = ReportTableColumn::getInstance();
            $headerCols = $columnInstance->defaultColumnsOnEmptyQuery($block, $currentParams);
            return [$headerCols, []];
        }
        $uniqueFields = $this->getAllUniqueFields($queriedData);
        // config from admin
        $columns = $block->getLines->sortby('order_no');
        $xAxes2ndHeaders = $this->get2ndCols($block);
        $headerCols = [];

        $fields = [];

        foreach ($columns as $line) {
            $dataIndex = $line->data_index;
            $isActive = $line->is_active;
            $fields[] = $dataIndex;
            if ($isActive && in_array($dataIndex, $uniqueFields)) {
                $aggFooter = $this->getAggName($line->agg_footer);
                $title = $this->createHeaderTitle($line->title ?? $line->name, $line->icon, $line->icon_position, $currentParams);
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
        if ($block->is_transformed_data && ($x = $block->transformed_data_string)) {
            $transformedOpt = $this->sortData($x, true);
            $transformedCols = $this->getTransformedDataCols($fields, $transformedOpt, $transformedFields);
            $headerCols = array_merge($headerCols, $transformedCols);
            $xAxes2ndHeaders = $this->create2ndHeaderCols($headerCols, $block, $currentParams);
        }
        return [$headerCols, $xAxes2ndHeaders];
    }

    private function get2ndCols($block)
    {
        $data2ndHeader = [];
        $headerLines = $block->get2ndHeaderLines; #()->getParent();
        foreach ($headerLines as $column) {
            // dd($column);
            $parent  = $column->getParent;
            if ($parent->is_active) {
                $content = $this->createHeaderTitle($column->name, $column->icon, $column->icon_position);
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

    public function getTransformedDataCols($fields, $transformedOpt, $transformedFields)
    {
        $columns = [];
        if ($transformedFields) {
            $lastTransformedData = last($transformedOpt);
            $customCols = $lastTransformedData['custom_columns'] ?? [];
            $customColFields = array_map(fn($item) => $item['data_index'], $customCols);
            foreach ($transformedFields as $key) {
                if (in_array($key, $fields) || in_array($key, $customColFields)) continue;
                $columns[] = [
                    'dataIndex' => $key,
                    'align' => $lastTransformedData['align'] ?? 'center',
                    'footer' => $lastTransformedData['footer'] ?? '',
                    'width' => $lastTransformedData['width'] ?? 100,
                    'fixed' => $lastTransformedData['fixed'] ?? '',
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
                        'fixed' => $value['fixed'] ?? '',
                    ];
                    // set position columns
                    $position = $value['position'] ?? count($columns);
                    $position = is_numeric($position) ?  $position : ($position == 'end' ? count($columns) : 0);
                    array_splice($columns, $position, 0, [$newColumn]);
                }
            }
        }
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
            if (!$column->is_active) continue;
            if (in_array($column->data_index, $dataIndexToRender)) {
                $result[$column->data_index] = $column;
            }
        }
        return $result;
    }

    public function updateConfiguredCols($headerCols)
    {
        $columns = [];
        foreach ($headerCols as $value) {
            $columns[$value['dataIndex']] = [
                "name" =>  $value['title'] ?? $value['dataIndex'],
            ];
        }
        return $columns;
    }
}

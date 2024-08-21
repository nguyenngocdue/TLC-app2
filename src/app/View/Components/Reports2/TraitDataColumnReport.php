<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use Illuminate\Support\Facades\DB;
use Exception;

trait TraitDataColumnReport
{
    use TraitCreateSQLReport2;
    use TermsBlockReport;

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
            $collection = collect($sqlData);
            return $collection;
        }
        return collect();
    }

    public function getAllUniqueFields($collection)
    {
        return $collection->map(function ($item) {
            return array_keys(get_object_vars($item));
        })->reduce(function ($carry, $keys) {
            return $carry === null ? $keys : array_unique(array_merge($carry, $keys));
        }, null);
        // Tofix: test this
        // return array_unique(array_keys((array)$collection->first()));
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

    public function getKKKColumns($block, $params, $queriedData)
    {
        if (empty($queriedData)) {
            $queriedData = $this->getDataSQLString($block, $params);
        }
        $uniqueFields = $this->getAllUniqueFields($queriedData);
        //To fix getLines()->get()
        $lines = $block->getLines()->get()->sortby('order_no');
        $dataHeader = $this->getSecondColumns($block);

        $columns = [];
        //To fix: check if $processedIndices is needed
        $processedIndices = [];
        foreach ($lines as $line) {
            $dataIndex = $line->data_index;
            $isActive = $line->is_active;
            if ($isActive && !in_array($dataIndex, $processedIndices) && in_array($dataIndex, $uniqueFields)) {
                $processedIndices[] = $dataIndex;
                $aggFooter = $this->getAggName($line->agg_footer);
                $title = $this->createIconPosition($line->title ?? $line->name, $line->icon, $line->icon_position);
                $columns[] = [
                    'title' => $title,
                    'dataIndex' => $dataIndex,
                    'width' => $line->width,
                    'colspan' => $line->col_span,
                    'footer' => $aggFooter,
                ];
            }
        }
        if (empty($columns)) $columns = [['dataIndex' => null]];
        return [$queriedData, $columns, $dataHeader];
    }
}

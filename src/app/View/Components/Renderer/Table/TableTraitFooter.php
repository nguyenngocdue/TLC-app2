<?php

namespace App\View\Components\Renderer\Table;

trait TableTraitFooter
{
    function makeOneFooter($column, $tableName, $dataSource)
    {

        $aggList = [
            'agg_none',
            'agg_count_all',
            'agg_sum',
            'agg_avg',
            'agg_median',
            'agg_min',
            'agg_max',
            'agg_range',

            // 'agg_count_values',
            // 'agg_count_unique_values',
            // 'agg_count_empty',
            // 'agg_count_not_empty',
            // 'agg_percent_empty',
            // 'agg_percent_not_empty',
        ];
        $fieldName = $column['dataIndex'];
        $items = $dataSource->map(fn ($item) => is_object($item[$fieldName]) ? $item[$fieldName]->value : $item[$fieldName]);

        $result['agg_none'] = '';
        $result['agg_count_all'] = $items->count();
        $result['agg_sum'] = round($items->sum(), 2);
        $result['agg_avg'] = round($items->avg(), 2);
        $result['agg_median'] = $items->median();
        $result['agg_min'] = $items->min();
        $result['agg_max'] = $items->max();
        $result['agg_range'] = $result['agg_max'] - $result['agg_min'];

        $class = "focus:outline-none border-0 bg-transparent w-full h-6 block text-right pr-6 py-0";
        $inputs = [];
        $footer = $column['footer'];
        // echo $footer;
        foreach ($aggList as $agg) {
            $bg = ($footer == $agg) ? "text-blue-700" : "hidden";
            $id = "{$tableName}[footer][{$fieldName}][$agg]";
            $inputs[] = "<input id='$id' title='$agg' component='TraitFooter' value='$result[$agg]' readonly class='$class $bg' onChange='onChangeDropdown4AggregateFromTable(\"$id\", this.value)'/>";
        }
        return join("", $inputs);
    }

    function makeFooter($columns, $tableName, $dataSource)
    {
        $result0 = [];
        $hasFooter = false;
        foreach ($columns as $column) {
            if (isset($column['invisible']) && $column['invisible']) continue;
            if (isset($column['footer'])) {
                $hasFooter = true;
                $result0[$column['dataIndex']] = $this->makeOneFooter($column, $tableName, $dataSource);
            } else {
                if (isset($column['dataIndex'])) $result0[$column['dataIndex']] = "";
            }
        }
        if (!$hasFooter) return;

        $result0 = array_map(fn ($c) => "<td>" . $c . "</td>", $result0);
        // dump($result0);
        return join("", $result0);
    }
}

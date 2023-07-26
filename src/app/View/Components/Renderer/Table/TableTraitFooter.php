<?php

namespace App\View\Components\Renderer\Table;

trait TableTraitFooter
{
    function makeOneFooter($column, $tableName, $dataSource)
    {
        $fieldName = $column['dataIndex'];
        $items = $dataSource->map(fn ($item) => is_object($item[$fieldName]) ? $item[$fieldName]->value : $item[$fieldName]);
        $sum = round($items->sum(), 2);
        // $sum = array_sum(array_column($dataSource->toArray(), $fieldName));
        $class = "focus:outline-none border-0 bg-transparent w-full h-6 block text-right pr-6 py-0";
        $id = "{$tableName}[footer][{$fieldName}]";
        return "<input id='$id' component='TraitFooter' value='$sum' readonly class='$class' onChange='onChangeDropdown4AggregateFromTable(\"$id\", this.value)'/>";
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

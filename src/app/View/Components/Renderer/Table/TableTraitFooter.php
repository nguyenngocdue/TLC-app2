<?php

namespace App\View\Components\Renderer\Table;

use Illuminate\Support\Facades\Blade;

trait TableTraitFooter
{
    function makeOneFooter($column, $tableName)
    {
        $fieldName = $column['dataIndex'];
        $class = "focus:border-0 border-0 bg-transparent w-full h-10 block text-right pr-6 py-0";
        $id = "{$tableName}[footer][{$fieldName}]";
        return "<input id='$id' readonly class='$class' onChange='onChangeDropdown4AggregateFromTable(\"$id\", this.value)'/>";
    }

    function makeFooter($columns, $tableName)
    {
        $result0 = [];
        $hasFooter = false;
        foreach ($columns as $column) {
            if (isset($column['invisible'])) continue;
            if (isset($column['footer'])) {
                $hasFooter = true;
                $result0[$column['dataIndex']] = $this->makeOneFooter($column, $tableName);
            } else {
                $result0[$column['dataIndex']] = "";
            }
        }
        if (!$hasFooter) return;

        $result0 = array_map(fn ($c) => "<td>" . $c . "</td>", $result0);
        // dump($result0);
        return join("", $result0);
    }
}

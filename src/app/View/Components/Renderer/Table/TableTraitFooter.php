<?php

namespace App\View\Components\Renderer\Table;

use Illuminate\Support\Facades\Blade;

trait TableTraitFooter
{
    function makeOneFooter($column, $dataSource)
    {
        return "A";
    }

    function makeFooter($columns, $dataSource)
    {
        $result0 = [];
        $hasFooter = false;
        foreach ($columns as $column) {
            if (isset($column['invisible'])) continue;
            if (isset($column['footer'])) {
                $hasFooter = true;
                $result0[$column['dataIndex']] = $this->makeOneFooter($column, $dataSource);
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

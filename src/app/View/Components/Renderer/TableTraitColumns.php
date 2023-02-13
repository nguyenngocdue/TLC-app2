<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;

trait TableTraitColumns
{
    private function isInvisible($column)
    {
        return (isset($column['invisible']) && $column['invisible'] == true);
    }

    private function makeColGroup($columns)
    {
        $result = [];
        foreach ($columns as $column) {
            if ($this->isInvisible($column)) continue;
            $name = $column['dataIndex'];
            if (isset($column['width']) && $column['width'] != '') {
                $w = $column['width'];
                $result[] = "<col name='$name' style='width:{$w}px'>";
            } else {
                $result[] = "<col name='$name'>";
            }
        }
        return join("", $result);
    }

    private function makeTh($column)
    {
        $renderer = $column['renderer'] ?? "_no_renderer_";
        $dataIndex = $column['dataIndex'];
        $columnName = $column['column_name'] ?? $dataIndex;
        $width = $column['width'] ?? "";
        $title = $column['title'] ?? Str::headline($column['dataIndex']);
        $tooltip = "DataIndex: $dataIndex\n";
        $tooltip .= "ColumnName: $columnName\n";
        $tooltip .= "Renderer: $renderer\n";
        $tooltip .= "Width: $width";
        return "<th class='{$dataIndex}_th px-4 py-3' title='$tooltip'>{$title}</th>";
    }

    private function getColumnRendered($columns)
    {
        $columnsRendered = [];
        array_walk($columns, function ($column, $key) use (&$columnsRendered) {
            if (!$this->isInvisible($column)) {
                $columnsRendered[] = $this->makeTh($column, $key);
            }
        });
        $columnsRendered = join("", $columnsRendered);
        return $columnsRendered;
    }

    private function makeNoColumn($columns)
    {
        $columnNo = ["title" => "No.", "renderer" => "no.", "dataIndex" => "auto.no.", 'align' => 'center', "width" => '10'];
        if ($this->showNo) array_unshift($columns, $columnNo);
        if ($this->showNoR) array_push($columns, $columnNo);
        return $columns;
    }

    private function makeThHeader($columns, $dataHeader)
    {
        if (is_null($dataHeader)) return "";
        $th = [];
        foreach ($columns as $column) {
            if (isset($dataHeader[$column['dataIndex']])) $th[] = "<th class='py-1'>" . $dataHeader[$column['dataIndex']] . "</th>";
            else $th[] = "<th></th>";
        }
        $result = join($th);
        return $result;
    }

    private function hideColumns($columns)
    {
        $columns = array_filter($columns, fn ($column) => !(isset($column['hidden']) &&  $column['hidden'] == true));
        // dump($columns);
        return $columns;
    }
}

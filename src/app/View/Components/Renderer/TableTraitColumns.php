<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;

trait TableTraitColumns
{
    private function makeColumn($column)
    {
        $renderer = $column['renderer'] ?? "_no_renderer_";
        $dataIndex = $column['dataIndex'];
        $title = $column['title'] ?? Str::headline($column['dataIndex']);
        return "<th class='{$dataIndex}_th px-4 py-3' title=\"{$dataIndex} / {$renderer}\">{$title}</th>";
    }

    private function makeColGroup($columns)
    {
        $result = [];
        foreach ($columns as $column) {
            $name = $column['dataIndex'];
            if (isset($column['width'])) {
                $w = $column['width'];
                $result[] = "<col name='$name' style='width: {$w}px'>";
            } else {
                $result[] = "<col name='$name'>";
            }
        }
        return join("", $result);
    }

    private function getColumnRendered($columns)
    {
        $columnsRendered = [];
        array_walk($columns, function ($column, $key) use (&$columnsRendered) {
            $columnsRendered[] = $this->makeColumn($column, $key);
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
        if (is_null($dataHeader)) return null;
        $th = [];
        foreach ($columns as $column) {
            if (isset($dataHeader[$column['dataIndex']])) $th[] = "<th class='py-1'>" . $dataHeader[$column['dataIndex']] . "</th>";
            else $th[] = "<th></th>";
        }
        $result = join($th);
        return $result;
    }
}

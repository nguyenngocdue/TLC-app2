<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\SuperProps;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

trait TraitEntityExportCSV
{
    private $tableName = 'table01';
    private function makeTd($columns, $dataLine, $no, $dataLineIndex, $tableDebug)
    {
        $tds = [];
        foreach (array_values($columns) as $index => $column) {
            $renderer = $column['renderer'] ?? false;
            $columnName = $column['column_name'] ?? $column['dataIndex'];
            $name = isset($column['dataIndex']) ? "{$this->tableName}[$columnName][$dataLineIndex]" : "";

            switch ($renderer) {
                case  'no.':
                    $rendered = $no;
                    break;
                default:
                    $dataIndex = $column['dataIndex'];
                    if (str_contains($dataIndex, "()")) {
                        $fn = substr($dataIndex, 0, strlen($dataIndex) - strlen("()"));
                        $rawData = $dataLine->$fn() ?? ""; //this is to execute the getCheckedByField function
                    } else {
                        $rawData = $dataLine[$dataIndex] ?? "";
                    }
                    $rawData = is_array($rawData) ? count($rawData) . " items" : $rawData;
                    break;
            }
        }
        return $tds;
    }
    private function makeNoColumn2($columns)
    {
        $columnNo = ["title" => "No.", "renderer" => "no.", "dataIndex" => "auto.no.", 'align' => 'center', "width" => '10'];
        if (true) array_unshift($columns, $columnNo);
        return $columns;
    }

    private function makeTrTd($columns, $dataSource, $tableDebug)
    {
        $columns = $this->makeNoColumn2($columns);
        $trs = [];
        $items = $dataSource;
        $lastIndex = "anything";
        foreach ($dataSource as $no => $dataLine) {
            $tds = $this->makeTd($columns, $dataLine, $no + 1, $no, $tableDebug);
            $tr =  join("", $tds);
            $trs[] = $tr;

            if (isset($dataLine['rowDescription'])) {
                dump($dataLine['rowDescription']);
            }
        }
        $tr_td = join("", $trs);
        // dump($tr_td);
        return $tr_td;
    }
    private function getAttributeRendered($column, $dataLine)
    {
        $attributes = $column['attributes'] ?? [];
        array_walk($attributes, fn (&$value, $key) => $value = isset($dataLine[$value]) ? "$key='$dataLine[$value]'" : "_no_$value");
        $attributeRendered = trim(join(" ", $attributes));
        return $attributeRendered;
    }

    private function getPropertyRendered($column)
    {
        $properties = $column['properties'] ?? [];
        array_walk($properties, fn (&$value, $key) => $value = "$key='$value'");
        $propertyRendered = trim(join(" ", $properties));
        return $propertyRendered;
    }
    private function getRendererParams($column)
    {
        $str = (is_array($column['rendererParam'])) ? json_encode($column['rendererParam']) : $column['rendererParam'];
        return "rendererParam='$str'";
    }

    private function applyRender($name, $renderer, $rawData, $column, $dataLine, $index)
    {
        // dump(SuperProps::getFor($this->type));
        // dump($column);
        // dump($dataLine);
        // dump($rawData);
    }
}

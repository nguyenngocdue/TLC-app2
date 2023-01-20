<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

trait TableTraitApplyRender
{
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

    private function applyRender($renderer, $rawData, $column, $dataLine, $index)
    {
        $name = isset($column['dataIndex']) ? "name='{$column['dataIndex']}[$index]'" : "";
        $attributeRender = $this->getAttributeRendered($column, $dataLine);
        $propertyRender = $this->getPropertyRendered($column, $dataLine);
        $typeRender = isset($column['type']) ? "type='{$column['type']}'" : "";
        $sortByRender = isset($column['sortBy']) ? "sortBy='{$column['sortBy']}'" : "";

        $needCbbDataSource = isset($column['renderer']) && in_array($column['renderer'], ['dropdown']);
        $cbbDataSource = $column['cbbDataSource'] ?? ["", "true"];
        $cbbDataSourceRender = $needCbbDataSource ? ':cbbDataSource=\'$cbbDataSource\'' : "";
        $dataLineRender = $dataLine ? ':dataLine=\'$dataLine\'' : "";
        $columnRender = $column ? ':column=\'$column\'' : "";
        $cellRender = ':cell=\'$cell\'';
        $rendererParam = isset($column['rendererParam']) ? $this->getRendererParams($column) : "";
        $formatterName = isset($column['formatterName']) ? "formatterName='{$column['formatterName']}'" : "";

        $attributes = "$name $attributeRender $propertyRender $typeRender $cbbDataSourceRender ";
        $attributes .= "$dataLineRender $columnRender $cellRender $rendererParam $formatterName ";
        $attributes .= "$sortByRender ";
        $attributes = Str::of($attributes)->replaceMatches('/ {2,}/', ' '); //<< Remove double+ space

        $editable = isset($column['editable']) ? ".editable" : "";
        $tagName = "x-renderer{$editable}.{$renderer}";

        $output = "<$tagName $attributes>$rawData</$tagName>";
        // if ($editable) Log::info($output);
        // Log::info($output);
        // Log::info($column);
        $cell = $dataLine[$column['dataIndex']] ?? "No dataIndex for " . $column['dataIndex']; //This is for Thumbnail
        $params = ['column' => $column, 'dataLine' => $dataLine, 'cell' => $cell,];
        if ($needCbbDataSource) $params['cbbDataSource'] = $cbbDataSource;
        $blade = Blade::render($output, $params);
        return $blade;
    }
}

<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
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
        $tableName = $this->tableName;
        $columnName = $column['column_name'] ?? $column['dataIndex'];
        $name = isset($column['dataIndex']) ? "name='{$tableName}[$columnName][$index]'" : "";
        $attributeRender = $this->getAttributeRendered($column, $dataLine);
        $propertyRender = $this->getPropertyRendered($column, $dataLine);
        $typeRender = isset($column['type']) ? "type='{$column['type']}'" : "";
        $sortByRender = isset($column['sortBy']) ? "sortBy='{$column['sortBy']}'" : "";

        $isDropdown = isset($column['renderer']) && in_array($column['renderer'], ['dropdown']);
        $cbbDataSource = $column['cbbDataSource'] ?? ["", "true"];
        $cbbDataSourceRender = $isDropdown ? ':cbbDataSource=\'$cbbDataSource\'' : "";
        $dataLineRender = $dataLine ? ':dataLine=\'$dataLine\'' : "";
        $columnRender = $column ? ':column=\'$column\'' : "";
        $cellRender = ':cell=\'$cell\'';
        $rendererParam = isset($column['rendererParam']) ? $this->getRendererParams($column) : "";
        $formatterName = isset($column['formatterName']) ? "formatterName='{$column['formatterName']}'" : "";
        $onChange = isset($column['onChange']) ? "onChange='{$column['onChange']}'" : "";

        $attributes = "$name $attributeRender $propertyRender $typeRender $cbbDataSourceRender ";
        $attributes .= "$dataLineRender $columnRender $cellRender $rendererParam $formatterName $onChange";
        $attributes .= "$sortByRender ";
        $attributes = Str::of($attributes)->replaceMatches('/ {2,}/', ' '); //<< Remove double+ space

        $isEditable = (isset($column['editable']) && $column['editable'] == true);
        $editableStr = $isEditable ? ".editable" : "";
        $tagName = "x-renderer{$editableStr}.{$renderer}";
        if ($column['renderer'] === 'dropdown4') {
            $tagName = "x-controls.has-data-source.dropdown4";
            $table01Name = $column['table01Name'];
            $multiple = (isset($column['multiple']) && $column['multiple'] == true) ? "multiple=true" : "";
            $tableName = $column['table'];
            $lineType = $column['lineType'];
            $attributes = "$name $typeRender $multiple
                                selected='[$rawData]' 
                                table01Name='$table01Name' 
                                tableName='$tableName' 
                                lineType='$lineType' 
                                rowIndex='$index'
                                ";
            $output = "<$tagName $attributes></$tagName>";
            // Log::info($output);

            $blade = Blade::render($output);
            return $blade;
        }

        $output = "<$tagName $attributes>$rawData</$tagName>";
        // Log::info($output);
        // Log::info($column);
        $cell = $dataLine[$column['dataIndex']] ?? "No dataIndex for " . $column['dataIndex']; //This is for Thumbnail
        if ($isEditable && $isDropdown) {
            if (is_string($cell)) {
                $instance = json_decode($cell);
                if (!is_null($instance) && isset($instance->id)) $cell = $instance->id;
            } else {
                //$cell is ['value', 'cbbDS'] format
            }
        }

        $params = ['column' => $column, 'dataLine' => $dataLine, 'cell' => $cell,];
        if ($isDropdown) $params['cbbDataSource'] = $cbbDataSource;
        $blade = Blade::render($output, $params);
        return $blade;
    }
}

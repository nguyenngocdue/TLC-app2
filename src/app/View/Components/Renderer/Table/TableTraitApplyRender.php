<?php

namespace App\View\Components\Renderer\Table;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TableTraitApplyRender
{
    private function getAttributeRendered($column, object $dataLineObj)
    {
        $attributes = $column['attributes'] ?? [];
        foreach ($attributes as $key => &$att) {
            if (isset($dataLineObj->$att)) {
                $value = $dataLineObj->$att;
                $att = "$key='$value'";
            } else {
                $att = "_no_$att";
            }
        }
        // array_walk($attributes, fn (&$value, $key) => $value = isset($dataLine->{$value}) ? "$key='$dataLine->{$value}'" : "_no_$value");
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
        $strParam = (is_array($column['rendererParam'])) ? json_encode($column['rendererParam']) : $column['rendererParam'];
        $rendererUnit = $column['rendererUnit'] ?? ""; //<< CONFIG_MIGRATE
        $strUnit = (is_array($rendererUnit)) ? json_encode($rendererUnit) : $rendererUnit;
        return "rendererParam='$strParam' rendererUnit='$strUnit'";
    }

    private function applyRender($name, $renderer, $rawData, $column, object $dataLineObj, $index, $batchLength)
    {
        $name = $name ? "name='$name'" : "";
        $rowIndexRender = "rowIndex='$index'";

        $attributeRender = $this->getAttributeRendered($column, $dataLineObj);
        $propertyRender = $this->getPropertyRendered($column);
        $typeRender = isset($column['type']) ? "type='{$column['type']}'" : "";
        $sortByRender = isset($column['sortBy']) ? "sortBy='{$column['sortBy']}'" : "";

        $isDropdown = isset($column['renderer']) && in_array($column['renderer'], ['dropdown']);
        $cbbDataSource = $column['cbbDataSource'] ?? ["", "true"];
        $cbbDataSourceRender = $isDropdown ? ':cbbDataSource=\'$cbbDataSource\'' : "";
        $dataLineRender = $dataLineObj ? ':dataLine=\'$dataLine\'' : "";
        $columnRender = $column ? ':column=\'$column\'' : "";
        $cellRender = ':cell=\'$cell\'';
        $rendererParam = isset($column['rendererParam']) ? $this->getRendererParams($column) : "";
        $formatterName = isset($column['formatterName']) ? "formatterName='{$column['formatterName']}'" : "";
        $onChange = isset($column['onChange']) ? "onChange='{$column['onChange']}'" : "";
        $rowReadOnly = (isset($dataLineObj->readOnly) && $dataLineObj->readOnly) ? 'readOnly=\'true\'' : '';

        $attributes = "$name $attributeRender $propertyRender $typeRender $cbbDataSourceRender ";
        $attributes .= "$dataLineRender $columnRender $cellRender $rendererParam $formatterName $onChange ";
        $attributes .= "$sortByRender $rowIndexRender $rowReadOnly";

        //This to make sure in print mode, comment will be displayed in full
        if ($renderer === 'text' && $this->noCss) {
            $attributes .= " truncate=0 ";
        }

        if (env("CONTROL_TRUE_WIDTH")) {
            $styleRender = isset($column['width']) ? 'style="width: ' . $column['width'] . 'px;"' : '';
            $attributes .= "$styleRender ";
        }
        $attributes = Str::of($attributes)->replaceMatches('/ {2,}/', ' '); //<< Remove double+ space

        $isEditable = (isset($column['editable']) && $column['editable'] == true);
        $editableStr = $isEditable ? ".editable" : "";
        $tagName = "x-renderer{$editableStr}.{$renderer}";
        if ($column['renderer'] === 'dropdown4') {
            $tagName = "x-controls.has-data-source.dropdown4";
            $multiple = (isset($column['multiple']) && $column['multiple'] == true) ? "multiple=true" : "";
            $deaf = (isset($column['deaf']) && $column['deaf'] == true) ? "deaf=true" : "";
            $saveOnChangeRenderer = (isset($column['saveOnChange']) && $column['saveOnChange'] == true) ? "saveOnChange=true" : "";
            $batchLength = "batchLength=$batchLength";
            $rawData = ($rawData instanceof EloquentCollection || $rawData instanceof SupportCollection) ? $rawData = $rawData->pluck('id') : [$rawData];
            $rawData = json_encode($rawData);
            $attributes = "$name $typeRender $multiple $deaf $propertyRender $rowReadOnly ";
            $attributes .= " $rowIndexRender selected='$rawData' $saveOnChangeRenderer $batchLength";
            // if (App::isLocal()) {
            //     $styleRender = isset($column['width']) ? 'style="width: ' . $column['width'] . 'px;"' : '';
            //     $attributes .= "$styleRender ";
            // }
            $output = "<$tagName $attributes></$tagName>";
            // Log::info($output);

            $blade = Blade::render($output);
            return $blade;
        }

        $output = "<$tagName $attributes>";
        $output .= $rawData;
        $output .= "</$tagName>";

        // Log::info($output);
        // Log::info($column);

        $columnName = $column['dataIndex'];
        $cell = $dataLineObj->$columnName ?? "No dataIndex for " . $column['dataIndex']; //This is for Thumbnail
        if ($isEditable && $isDropdown) {
            if (is_string($cell)) {
                $instance = json_decode($cell);
                if (!is_null($instance) && isset($instance->id)) $cell = $instance->id;
            } else {
                //$cell is ['value', 'cbbDS'] format
            }
        }
        //<< As Blade:render will auto convert $cell to htmlspecialchars, so the cell have to send a clone of itself
        //<< Then next time when the same cell is used in different columns, it is still the object, not &quote string.
        // $cell =  is_object($cell) ? clone $cell : $cell;

        //<<This cause View All Table not rendering avatar correctly if it already rendered once.
        // if (is_object($cell)) {
        //     // dump($cell);
        //     $rendered = [];
        //     switch ($column['renderer']) {
        //         case 'renderAllChildrenAndClickToOpenChildren':
        //             foreach ($cell as $key => $value) {
        //                 if (isset($value->name)) {
        //                     $rendered[] = "<div>" . $key + 1 . ". " . $value->name . "</div>";
        //                 }
        //             }
        //             $allChildren = "<div class='mx-4'>" . join('', $rendered,) . "</div>";
        //             // dump($dataLineObj);
        //             $routeName = $dataLineObj->getTable() . ".edit";
        //             // dd($routeName);
        //             $route = route($routeName, $dataLineObj->id);
        //             $button = "<i class='fas fa-pencil-alt'>";
        //             $button = "<a href='$route' target='_blank'>$button </a>";
        //             $button = "<span class='text-blue-600 mx-4 my-auto'>$button</span>";
        //             return "<div class='flex justify-between'>" . $allChildren . $button . "</div>";
        //             break;
        //             // default:
        //             //     return "Unknown how to render $column[renderer] for $columnName.";
        //             //     break;
        //     }
        // }

        $cell =  is_object($cell) ? "Take data[slot] instead." : $cell;

        $params = ['column' => $column, 'dataLine' => $dataLineObj, 'cell' => $cell];
        if ($isDropdown) $params['cbbDataSource'] = $cbbDataSource;
        $blade = Blade::render($output, $params);
        return $blade;
    }
}

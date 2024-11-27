<?php

namespace App\View\Components\Reports2;


trait TraitReportDetectVariableChanges 
{

    use TraitReportTermNames;
    use TraitReportFormatString;

    function formatStrByRendererType($valueInData, $rendererType){
        $formattedValue = '';
        switch($rendererType){
            case $this->CHART_TYPE_ID:
                $formattedValue = '['. implode(',' , array_map(fn($item) => is_numeric($item) ? $item : "\"{$item}\"",$valueInData)) . ']';
                // $formattedValue = '['. implode(',' , array_map(fn($item) => is_numeric($item) ? $item : "'{$item}'",$valueInData)) . ']';
                break;
            case $this->PARAGRAPH_TYPE_ID:
                $formattedValue = trim(implode(',' , array_map(fn($item) => "\"{$item}\"",$valueInData)), '"');
                break;
            default:
                break;
        }
        return $formattedValue;
    }

    function detectVariables($string, $currentParams, $queriedData)
    {
        $rendererType = $this->block->renderer_type;
        $parsedVariables = $this->parseVariables($string);
        foreach (last($parsedVariables) as $key => $value) {
            $variable = trim(str_replace('$', '', $value));
            $firstMatches = reset($parsedVariables);
            $keyInOptions = $firstMatches[$key];
            if (str_contains($variable, 'QRY_'))  {
                $variable = str_replace('QRY_', '', $variable);
                $valueInData = $queriedData->pluck($variable)->toArray();
                $formattedValue = $this->formatStrByRendererType($valueInData, $rendererType);
                $string = trim(str_replace($keyInOptions, $formattedValue, $string),'"');
            }else {
                $changedVal = isset($currentParams[$variable]) ? $currentParams[$variable] : 'null';
                $string = str_replace($keyInOptions, $changedVal, $string);
            }
            $string = $this->evaluateAGG($string);
        }
        // dd($string);
        return $string;
    }

}


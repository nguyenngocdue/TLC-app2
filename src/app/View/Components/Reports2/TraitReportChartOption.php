<?php

namespace App\View\Components\Reports2;


trait TraitReportChartOption
{
    use TraitReportDetectVariableChanges;

    function changeToJsonOptions($optionStr, $queriedData)
    {
        $transformedFields = $this->transformedFields;
        if($transformedFields) {
            $optionStr = $this->changeToJsonOptionsByTransformation($optionStr, $queriedData, $transformedFields);
        }
        $optionStr = $this->detectVariables($optionStr, $this->currentParams, $queriedData);
        $jsonOptions = json_decode($optionStr);
        if (is_null($jsonOptions))dump($optionStr);
        return $jsonOptions;
    }
   
}

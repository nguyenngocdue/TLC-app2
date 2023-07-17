<?php

namespace App\Http\Controllers;

use App\Utils\Support\StringPivotTable;

trait ParseFiltersToJsonReport
{
    function parseStringToJson($array)
    {
        $jsonData = [];
        foreach ($array as $value) {
            $jsonData = array_merge($jsonData,self::convertToJSON(($value)));
        }
        return $jsonData;
    }

    private static function convertToJSON($input)
    {
        $input = explode('[', $input);
        $key = trim($input[0]);

        if (!isset($input[1])) {
            if ($key === 'picker_date') $typeRender = 'picker_date';
            return [$key => [
                'title' =>  'Enter title',
                'dataIndex' => $key,
                'multiple' =>  false,
                'allowClear' => false,
                'renderer' => $typeRender ?? null,
            ]];
        }
        $value = StringPivotTable::removeNonAlphabeticCharacters($input[1]);
        $value = explode('.', $value);
        $value = StringPivotTable::extractKeyValuePairs($value) + ['dataIndex' => $key];

        $dataIndex = isset($value['multiple']) &&  $value['multiple'] === 'true'  ? 'many_'.$value['dataIndex'] : $value['dataIndex'];
        $renderer = $value['renderer'] ?? null;
        if ($key === 'picker_date') {
            $dataIndex = 'picker_date';
            $renderer = 'picker_date';
        }
        return [$key => [
            'title' => $value['title'] ?? 'Enter title',
            'dataIndex' =>  $dataIndex,
            'multiple' => $value['multiple'] ?? false,
            'allowClear' => $value['allowClear'] ?? false,
            'renderer' =>  $renderer
        ]];
    }
}

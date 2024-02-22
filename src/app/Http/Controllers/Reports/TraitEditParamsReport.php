<?php

namespace App\Http\Controllers\Reports;


trait TraitEditParamsReport
{
    public function changeValueParams($params)
    {
        $editedParams = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $newValue = implode(',', $value);
                $editedParams[$key] = $newValue;
            } else $editedParams[$key] = $value;
        }
        return $editedParams;
    }
}

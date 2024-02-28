<?php

namespace App\Http\Services;

use App\Utils\Support\CurrentUser;

class DiginetGetEmployeeHoursService
{
    private function changeTypeDate($isoDateTime)
    {
        $timestamp = strtotime($isoDateTime);
        $formattedDateTime = date("Y-m-d H:i:s", $timestamp);
        return $formattedDateTime;
    }
    function changeFields($item, $fieldsNeedToChange)
    {
        $fieldsDiginet = array_keys($item);
        $array = [];
        foreach ($fieldsDiginet as $key => $field) {
            $val = $item[$field];
            if ($field == "Date") $val = $this->changeTypeDate($val);
            $array[$fieldsNeedToChange[$key]] = $val;
            $array['owner_id'] = CurrentUser::id();
        }
        return $array;
    }
}

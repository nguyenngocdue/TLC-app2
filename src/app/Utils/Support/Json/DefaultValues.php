<?php

namespace App\Utils\Support\Json;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;

class DefaultValues extends JsonGetSet
{
    protected static $filename = "default-values.json";

    public static function getAllOf($type)
    {
        $rawValues = parent::getAllOf($type);
        $cu = CurrentUser::get();

        foreach ($rawValues as $key => $value) {
            // Log::info('key ' . $key);
            // Log::info('value ' . $value['default_value']);
            switch ($value['default_value']) {
                case "cu-id()":
                    $rawValues[$key]['default_value'] = $cu->id;
                    break;
                case "cu-workplace-id()":
                    $rawValues[$key]['default_value'] = $cu->getWorkplace->id;
                    break;
                case "cu-employeeid-text()":
                    $rawValues[$key]['default_value'] = $cu->employeeid;
                    break;
                case "cu-position-id()":
                    $rawValues[$key]['default_value'] = $cu->position;
                    break;
                case "cu-hod-id()":
                    $rawValues[$key]['default_value'] = $cu->getUserDepartment->head_of_department;
                    break;
                case "cu-dda-id()":
                    $rawValues[$key]['default_value'] = $cu->getUserDiscipline->def_assignee;
                    break;
                case "cu-discipline-id()":
                    $rawValues[$key]['default_value'] = $cu->discipline;
                    break;
            }
        }

        // Log::info($rawValues);

        return $rawValues;
    }
}

<?php

namespace App\Http\Services;

use App\Utils\Support\APIDiginet;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

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

    function createAndUpdateData($params, $endpointName, $index = 0)
    {
        $modifiedName = str_replace('-', '_', $endpointName);
        $modelName = 'Diginet_' . $modifiedName;
        $modelPath = 'App\Models\\' . $modelName;
        $tableName = 'diginet_' . Str::plural($modifiedName);

        $year = substr($params['FromDate'], 0, 4);
        $month = substr($params['FromDate'], 5, 2);
        $modelIns = new $modelPath;
        $fieldsToMap = array_slice($m = $modelIns->getFillable(), 1, count($m) - 2);

        // get Diginet's datasource from api
        $data = APIDiginet::getDatasourceFromAPI($endpointName, $params)['data'];
        $data = $index ? $data[$index] : $data;


        if ($data) {
            $year = substr($params['FromDate'], 0, 4);
            $month = substr($params['FromDate'], 5, 2);

            DB::statement("ALTER TABLE $tableName AUTO_INCREMENT = 1;");
            DB::table($tableName)
                ->whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$year, $month])
                ->delete();

            foreach ($data as &$item) $item = $this->changeFields($item, $fieldsToMap);
            $recordCount = 0;
            foreach ($data as $row) {
                $modelPath::create($row);
                $recordCount++;
            }
            dump("Successfully added {$recordCount} rows to the database");
            dump($data);
            Toastr::success("Successfully added {$recordCount} rows to the database.");
        } else {
            dd("No data found to import into the database.");
        }
    }
}

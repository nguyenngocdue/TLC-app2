<?php

namespace App\Http\Services;

use App\Utils\Support\APIDiginet;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class DiginetService
{
    private function changeTypeDate($isoDateTime)
    {
        $timestamp = strtotime($isoDateTime);
        $formattedDateTime = date("Y-m-d H:i:s", $timestamp);
        return $formattedDateTime;
    }
    function changeFields($item, $fieldsToMap, $conFieldName = 'date')
    {
        if (empty($item)) return [];
        $fieldsDiginet =  array_combine($fieldsToMap, array_keys($item));
        $array = [];
        foreach ($fieldsDiginet as $key => $field) {
            $val = $item[$field];
            if ($key == $conFieldName) $val = $this->changeTypeDate($val);
            $array[$key] = $val;
            $array['owner_id'] = CurrentUser::id();
        }
        return $array;
    }

    function createAndUpdateData($modelName, $params, $endpointName, $conFieldName = 'date', $index = 0)
    {
        $modelPath = 'App\Models\\' . $modelName;

        $modelIns = new $modelPath;
        $fieldsToMap = array_slice($m = $modelIns->getFillable(), 1, count($m) - 3);
        $tableName = $modelIns->getTable();

        // get Diginet's datasource from api
        $data = APIDiginet::getDatasourceFromAPI($endpointName, $params)['data'];
        $data = $data[$index];
        $FromDate = substr($params['FromDate'], 0, 10);
        $toDate = substr($params['ToDate'], 0, 10);

        $response = ['status' => 'error', 'message' => 'No data found to import into the database.', 'recordsDeleted' => 0, 'recordsAdded' => 0];

        if ($data) {

            DB::statement("ALTER TABLE $tableName AUTO_INCREMENT = 1;");
            $del = DB::table($tableName)
                ->whereDate($conFieldName, '>=', $FromDate)
                ->whereDate($conFieldName, '<=', $toDate)
                ->delete();

            $response['recordsDeleted'] = $del;
            $response['message'] = "{$del} rows were deleted from {$tableName} from $FromDate to $toDate";


            foreach ($data as &$item) $item = $this->changeFields($item, $fieldsToMap, $conFieldName);
            $recordCount = 0;
            foreach ($data as $row) {
                $modelPath::create($row);
                $recordCount++;
            }
            $response['status'] = 'success';
            $response['on_table'] = $tableName;
            $response['message'] = "{$recordCount} rows have been successfully added to the database.";
            $response['recordsAdded'] = $recordCount;
        }
        return $response;
    }
}

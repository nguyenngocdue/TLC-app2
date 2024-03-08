<?php

namespace App\Http\Services;

use App\Events\ButtonClickEvent;
use App\Http\Controllers\DiginetHR\PageController\DiginetDataController;
use App\Utils\Support\APIDiginet;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // dd($item, $fieldsToMap);
        $fieldsDiginet =  array_combine($fieldsToMap, array_keys($item));
        $array = [];
        foreach ($fieldsDiginet as $key => $field) {
            $val = $item[$field];
            if ($key === $conFieldName) $val = $this->changeTypeDate($val);
            $array[$key] = $val;
            $array['owner_id'] = 1;
        }
        return $array;
    }

    function createAndUpdateData($modelName, $params, $endpointName, $conFieldName = 'date')
    {
        $modelPath = 'App\Models\\' . $modelName;

        $modelIns = new $modelPath;
        $fieldsToMap = array_slice($m = $modelIns->getFillable(), 1, count($m) - 3);
        $tableName = $modelIns->getTable();

        // sent API request
        $data = APIDiginet::getDatasourceFromAPI($endpointName, $params)['data'];

        // index data [0 => line, 1 => sheet]
        $index = str_contains($modelName, 'line') ? 0 : 1;
        $index = str_contains('employee_hour', $modelName) ? 0 : $index;

        $response = ['status' => 'error', 'message' => "No data found to import into [{$tableName}] table.", 'recordsDeleted' => 0, 'recordsAdded' => 0];
        if (empty($data)) return $response;
        // except key of data
        $data = array_values($data);
        $data = count($data) <= 2 ? $data[$index] : $data;

        if (empty($data)) return $response;
        if ($data) {
            $FromDate = substr($params['FromDate'], 0, 10);
            $toDate = substr($params['ToDate'], 0, 10);
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
            $response['period'] = $FromDate . ' - ' . $toDate;
            $response['table_on'] = $tableName;
            $response['message'] = "{$recordCount} rows have been successfully added to <strong>[{$tableName}]</strong> table.";
            $response['recordsAdded'] = $recordCount;
            Log::info($response);
        }
        return $response;
    }

    public function getDataAndStore($keys, $params)
    {
        $ins = new DiginetDataController();
        $dataRender = $ins->getDataRender();
        $dataRender = array_map(fn ($k) => $dataRender[$k], $keys);
        $result = [];
        foreach ($dataRender as $item) {
            $endpointNameDiginet = $item['endpoint_name_diginet'];
            $conFieldName = $item['field_index_name'];
            $modelName = $item['model_name'];
            $insService = new DiginetService();
            $result[] = $insService->createAndUpdateData($modelName, $params, $endpointNameDiginet, $conFieldName);
        }
        return response()->json($result);
    }
}

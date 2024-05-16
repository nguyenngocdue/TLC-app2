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

    private function editFieldsOfItems($items)
    {
        $keysArray = [];
        $keys = array_keys($items);
        foreach ($keys as $value) {
            $newKey = str_replace('ID', 'Id', $value);
            $newKey = str_replace('OT', 'Ot', $newKey);
            $newKey = str_replace('LA', 'La', $newKey);
            $newKey = str_replace('TB', 'Tb', $newKey);
            $newKey = str_replace('EmployeeId', 'employeeid', $newKey);
            $newKey = str_replace('WorkPlaceCode', 'WorkplaceCode', $newKey);

            $newKey = str_replace('TotalofTbDay', 'total_of_tb_day', $newKey);
            $newKey = str_replace('TotalofLaDay', 'total_of_la_day', $newKey);

            $newKey = str_replace('NumberofTbDay', 'number_of_tb_day', $newKey);
            $newKey = str_replace('NumberofLaDay', 'number_of_la_day', $newKey);


            $newKey = strtolower(preg_replace('/([A-Z])/', '_$1', $newKey));
            if ($newKey[0] == '_') {
                $newKey = substr($newKey, 1);
            }
            $keysArray[] = $newKey;
        }
        return $keysArray;
    }

    function changeFields($item, $fieldsToMap, $conFieldName = 'date')
    {
        if (empty($item)) return [];

        $editedKeys = self::editFieldsOfItems($item);
        $diffFields = array_diff($editedKeys, $fieldsToMap);
        if (!empty($diffFields)) return ['field_error' => $diffFields];
        $fieldsDiginet =  array_combine($fieldsToMap, array_keys($item));
        $array = [];
        foreach ($fieldsDiginet as $key => $field) {
            $val = $item[$field];
            if ($key === $conFieldName) $val = $this->changeTypeDate($val);
            if ($key === 'from_date' || $key === 'to_date') $val = $this->changeTypeDate($val);
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
        $data = APIDiginet::getDatasourceFromAPI($endpointName, $params);
        if (isset($data->original) && isset($data->original['error'])) {
            return dd($data->original['error']);
        }
        $data = APIDiginet::getDatasourceFromAPI($endpointName, $params)['data'];

        // index data [0 => line, 1 => sheet]
        $index = str_contains($modelName, 'line') ? 1 : 0;
        $index = str_contains('employee_hour', $modelName) ? 0 : $index;

        $response = ['status' => 'error', 'message' => "No data found to import into [{$tableName}] table.", 'recordsDeleted' => 0, 'recordsAdded' => 0];
        if (empty($data)) return $response;
        // except key of data
        $data = array_values($data);
        $data = count($data) <= 2 ? $data[$index] : $data;

        $fromDate = substr($params['FromDate'], 0, 10);
        $toDate = substr($params['ToDate'], 0, 10);

        if (empty($data)) return $response;
        if ($data) {
            DB::statement("ALTER TABLE $tableName AUTO_INCREMENT = 1;");
            $del = DB::table($tableName)
                ->whereDate($conFieldName, '>=', $fromDate)
                ->whereDate($conFieldName, '<=', $toDate)
                ->delete();

            $response['recordsDeleted'] = $del;
            $response['message'] = "{$del} rows were deleted from {$tableName} from $fromDate to $toDate";

            foreach ($data as &$item) {
                $item = $this->changeFields($item, $fieldsToMap, $conFieldName);
                if (isset($item['field_error'])) {
                    $response['status'] = 'error';
                    $response['period'] = $fromDate . ' - ' . $toDate;
                    $response['table_on'] = $tableName;
                    $response['message'] = "Please verify the following fields in the table (" . $tableName . ") or Diginet's API: " . json_encode(array_values($item['field_error']));
                    return $response;
                }
            }
            $recordCount = 0;
            foreach ($data as $row) {
                $modelPath::create($row);
                $recordCount++;
            }
            $response['status'] = 'success';
            $response['period'] = $fromDate . ' - ' . $toDate;
            $response['table_on'] = $tableName;
            $response['message'] = "Period of time: <strong>{$fromDate} -> {$toDate}</strong></br>{$recordCount} rows have been successfully added to <strong>[{$tableName}]</strong> table.";
            $response['recordsAdded'] = $recordCount;
            // Log::info($response);
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

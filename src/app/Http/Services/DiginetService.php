<?php

namespace App\Http\Services;

use App\Http\Controllers\DiginetHR\PageController\DiginetDataController;
use App\Utils\Support\APIDiginet;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\StringCustomize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Exception;

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

    function createAndUpdateData($modelName, $params, $endpointName, $conFieldName = 'date', $index)
    {
        $modelPath = 'App\Models\\' . $modelName;

        $modelIns = new $modelPath;
        $fieldsToMap = array_slice($m = $modelIns->getFillable(), 1, count($m) - 3);
        $tableName = $modelIns->getTable();

        $data = APIDiginet::getDatasourceFromAPI($endpointName, $params)['data'];

        $response = ['status' => 'error', 'message' => "No data found to import into [{$tableName}] table.", 'recordsDeleted' => 0, 'recordsAdded' => 0];
        if (empty($data)) return $response;
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
            $response['table_on'] = $tableName;
            $response['message'] = "{$recordCount} rows have been successfully added to [{$tableName}] table.";
            $response['recordsAdded'] = $recordCount;
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
            $dataIndex = $item['data_index'];
            $insService = new DiginetService();
            $result[] = $insService->createAndUpdateData($modelName, $params, $endpointNameDiginet, $conFieldName, $dataIndex);
        }
        return response()->json($result);
    }
}

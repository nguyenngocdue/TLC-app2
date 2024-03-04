<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Http\Services\DiginetService;
use Illuminate\Http\Request;

class TransferDiginetDataOvertimeLinesForApi extends Controller
{
    public function store(Request $request)
    {
        $params = $request->input();
        $ins = new DiginetDataController();
        $dataRender = $ins->getDataRender();
        $keys = ['employee-overtime-sheets', 'employee-overtime-lines'];
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

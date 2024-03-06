<?php

namespace App\Http\Controllers\DiginetHR\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DiginetService;
use Response;

class TransferALLDiginetDataForApi extends Controller
{
    public function update(Request $request)
    {
        $params = $request->input();
        $responses = [];
        if ($params) {
            $ins = new DiginetService();
            $operations = [
                ['business-trip-sheets', 'business-trip-lines'],
                ['employee-leave-sheets', 'employee-leave-lines'],
                ['employee-overtime-sheets', 'employee-overtime-lines'],
                ['employee-hours']
            ];

            foreach ($operations as $operation) {
                try {
                    $result = $ins->getDataAndStore($operation, $params);
                    $responses[] = ['operation' => implode(', ', $operation), 'status' => 'success', 'result' => $result];
                } catch (\InvalidArgumentException $e) {
                    $responses[] = ['operation' => implode(', ', $operation), 'status' => 'error', 'message' => $e->getMessage()];
                }
            }
        }
        return response()->json($responses);
    }
}

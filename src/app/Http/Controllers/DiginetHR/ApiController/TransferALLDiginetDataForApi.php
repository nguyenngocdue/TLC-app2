<?php

namespace App\Http\Controllers\DiginetHR\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Services\DiginetService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferALLDiginetDataForApi extends Controller
{
    protected $operations = [
        ['business-trip-sheets', 'business-trip-lines'],
        ['employee-leave-sheets', 'employee-leave-lines'],
        ['employee-overtime-sheets', 'employee-overtime-lines'],
        ['employee-hours']
    ];
    public function update(Request $request)
    {
        $params = $request->input();
        $responses = [];
        if ($params) {
            $ins = new DiginetService();
            $operations = $this->operations;
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

    public function delete(Request $request)
    {
        $results = [];
        foreach ($this->operations as $items) {
            foreach ($items as $value) {
                $tableName = 'diginet_' . str_replace('-', '_', Str::plural($value));

                $deleted = DB::table($tableName)->delete();
                $results[] = [
                    'table' => $tableName,
                    'deleted' => $deleted,
                    'message' => $deleted ?  "Data in [$tableName] table wasn't deleted" : "Data in [$tableName] table was deleted successfully"
                ];
                Log::info([$tableName, $deleted]);
            }
        }
        return response()->json([
            'results' => $results,
        ]);
    }
}

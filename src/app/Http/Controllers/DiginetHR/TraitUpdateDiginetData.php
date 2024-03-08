<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Services\DiginetService;

trait TraitUpdateDiginetData
{

    public function updateDiginetData($params)
    {
        $responses = [];
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
        return $responses;
    }
}

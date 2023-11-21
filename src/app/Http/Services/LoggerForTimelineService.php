<?php

namespace App\Http\Services;

use App\Models\Logger;
use Illuminate\Support\Facades\Log;

class LoggerForTimelineService
{
    function insert($currentValue, $previousValue,  $userId, $modelPath, $action = 'create')
    {
        $isLogger = false;
        switch ($action) {
            case 'update':
                if (isset($previousValue['status']) && isset($currentValue['status'])) {
                    $isLogger = ($previousValue['status'] !== $currentValue['status']);
                    $type = 'updated_field';
                    $key = 'entity_status';
                } else {
                    Log::error("Status of $modelPath not found. Cancelled Logger.");
                }
                break;
            case 'create':
            default:
                $isLogger = true;
                $type = 'created_entity';
                $key = 'create';
                break;
        }
        if ($isLogger) {
            if (!isset($currentValue['id'])) toastr()->warning(
                'Configuration is missing',
                'Please check config prop id in workflow hidden and visible'
            );
            Logger::create([
                'loggable_type' => $modelPath,
                'loggable_id' => $currentValue['id'] ?? null,
                'type' => $type,
                'key' => $key,
                'old_value' => $previousValue['status'] ?? null,
                'new_value' => $currentValue['status'] ?? null,
                'user_id' => $userId,
                'owner_id' => $userId,
                'created_at' => $currentValue['updated_at'] ?? null,
            ]);
        }
    }
}

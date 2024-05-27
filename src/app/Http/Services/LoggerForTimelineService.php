<?php

namespace App\Http\Services;

use App\Models\Logger;
use Illuminate\Support\Facades\Log;

class LoggerForTimelineService
{
    function insertForCreate($currentValue, $userId, $modelPath,)
    {
        $type = 'created_entity';
        $key = 'create';

        Logger::create([
            'loggable_type' => $modelPath,
            'loggable_id' => $currentValue['id'] ?? null,
            'type' => $type,
            'key' => $key,
            'user_id' => $userId,
            'owner_id' => $userId,
            // 'created_at' => $currentValue['updated_at'] ?? null,
        ]);
    }

    function insertForUpdate($currentValue, $previousValue,  $userId, $modelPath,)
    {
        if (!isset($previousValue['status']) || !isset($currentValue['status'])) {
            Log::info("$modelPath is statusless. Cancelled Logger.");
            return;
        }
        $isChanged = ($previousValue['status'] !== $currentValue['status']);
        if ($isChanged) {
            $type = 'updated_field';
            $key = 'entity_status';
            Logger::create([
                'loggable_type' => $modelPath,
                'loggable_id' => $currentValue['id'] ?? null,
                'type' => $type,
                'key' => $key,
                'old_value' => $previousValue['status'] ?? null,
                'new_value' => $currentValue['status'] ?? null,
                'user_id' => $userId,
                'owner_id' => $userId,
                // 'created_at' => $currentValue['updated_at'] ?? null,
            ]);
        }
    }
}

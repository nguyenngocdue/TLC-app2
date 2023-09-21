<?php

namespace App\BigThink;

use App\Events\StatusEnteredEvent;
use App\Events\StatusLeavingEvent;
use App\Http\Controllers\Workflow\LibStatuses;
use Exception;
use Illuminate\Support\Facades\Log;

trait HasStatus
{
    static function getAvailableStatuses()
    {
        $plural = static::getTableName();
        $statuses = LibStatuses::getFor($plural);
        return array_keys($statuses);
    }

    function transitionTo($newStatus)
    {
        $available = $this::getAvailableStatuses();
        $table = $this->getTable();
        if (!in_array($newStatus, $available)) {
            throw new Exception("The status [$newStatus] is not available for $table. Availabilities are [" . join(", ", $available) . "]");
        }

        $eventData = [
            'type' => $table,
            'id' => $this->id,
            'old_status' => $this->status,
            'new_status' => $newStatus,
        ];

        event(new StatusLeavingEvent($eventData));
        $this->status = $newStatus;
        $savedSuccessfully = $this->save();
        if ($savedSuccessfully) {
            event(new StatusEnteredEvent($eventData));
        }
        return true;
    }
}

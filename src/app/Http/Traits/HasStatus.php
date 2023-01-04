<?php

namespace App\Http\Traits;

use App\Events\StatusEnteredEvent;
use App\Events\StatusLeavingEvent;
use App\Http\Controllers\Workflow\LibStatuses;
use Exception;

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
        if (!in_array($newStatus, $available)) {
            throw new Exception("The status [$newStatus] is not available for this Model. Availabilities are [" . join(", ", $available) . "]");
        }

        $eventData = [
            'type' => $this->getTable(),
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
    // private function setStatus($valueInput, $data = null)
    // {
    //     if (!isset($valueInput['status'])) return false;
    //     $statusCurrent = $valueInput['status'];
    //     $item = Helper::getItemModel($this->type, $valueInput['id'] ?? $data->id);
    //     if ($statusCurrent === $item->status) return false;
    //     return $item->transitionTo($statusCurrent);
    // }
}

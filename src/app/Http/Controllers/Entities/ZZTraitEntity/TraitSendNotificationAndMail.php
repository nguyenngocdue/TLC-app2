<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\CreateNewDocumentEvent;
use App\Events\UpdatedDocumentEvent;
use App\Utils\Support\JsonControls;

trait TraitSendNotificationAndMail
{
    private function eventCreatedNotificationAndMail($fields, $id, $status, $type)
    {
        if ($status) {
            $fields = $this->addEntityType($fields, 'id', $id);
            $fields = $this->addEntityType($fields, 'status', $status);
            try {
                event(new CreateNewDocumentEvent($currentValue = $this->addEntityType($fields, 'entity_type', $type)));
            } catch (\Throwable $th) {
                dump($th);
            }
        }
    }
    private function eventUpdatedNotificationAndMail($previousValue, $fields, $type, $status)
    {
        if ($status) {
            try {
                event(new UpdatedDocumentEvent(
                    $previousValue = $this->addEntityType($previousValue, 'entity_type', $type),
                    $currentValue = $this->addEntityType($fields, 'entity_type', $type)
                ));
            } catch (\Throwable $th) {
                dump($th);
            }
        }
    }
    private function getPreviousValue($fields, $item)
    {
        $previousValue = [];
        foreach ($fields as $key => $value) {
            if ($key !== 'tableNames') {
                if (isset($item->$key)) {
                    $previousValue[$key] = $item->$key;
                } else {
                    if (in_array($key, JsonControls::getMonitors())) {
                        $key = str_replace('()', '', $key);
                        $valueGetMonitors = $item->{$key}()->pluck('id')->toArray();
                        $previousValue[$key] = $valueGetMonitors;
                    }
                }
            } else {
                $previousValue[$key] = $value;
            }
        }
        return $previousValue;
    }
}

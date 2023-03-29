<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\CreateNewDocumentEvent;
use App\Events\UpdatedDocumentEvent;
use App\Utils\Support\JsonControls;
use Brian2694\Toastr\Facades\Toastr;

trait TraitSendNotificationAndMail
{
    private function eventCreatedNotificationAndMail($fields, $id, $status, $type, $classType)
    {
        if ($status) {
            $fields = $this->addEntityType($fields, 'id', $id);
            $fields = $this->addEntityType($fields, 'status', $status);
            try {
                event(new CreateNewDocumentEvent($currentValue = $this->addEntityType($fields, 'entity_type', $type), $classType));
            } catch (\Throwable $th) {
                Toastr::error($th->getFile() . " " . $th->getLine(), $th->getMessage());
            }
        }
    }
    private function eventUpdatedNotificationAndMail($previousValue, $fields, $type, $status, $classType)
    {
        if ($status) {
            try {
                event(new UpdatedDocumentEvent(
                    $previousValue = $this->addEntityType($previousValue, 'entity_type', $type),
                    $currentValue = $this->addEntityType($fields, 'entity_type', $type),
                    $classType = $classType,
                ));
            } catch (\Throwable $th) {
                Toastr::error($th->getFile() . " " . $th->getLine(), $th->getMessage());
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
                        $fn = str_replace('()', '', $key);
                        $valueGetMonitors = $item->{$fn}()->pluck('id')->toArray();
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

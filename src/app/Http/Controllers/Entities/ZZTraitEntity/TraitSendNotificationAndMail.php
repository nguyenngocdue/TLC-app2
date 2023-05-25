<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\CreateNewDocumentEvent;
use App\Events\UpdatedDocumentEvent;
use App\Models\Logger;
use App\Utils\Support\JsonControls;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

trait TraitSendNotificationAndMail
{
    private function eventCreatedNotificationAndMail($fields, $id, $status, $type, $classType)
    {
        // dd($fields);
        if ($status) {
            $fields = $this->addEntityType($fields, 'id', $id);
            $fields = $this->addEntityType($fields, 'status', $status);
            try {
                $currentValue = $this->addEntityType($fields, 'entity_type', $type);
                $this->insertLogger($currentValue, null, Auth::id(), $classType);
                event(new CreateNewDocumentEvent($currentValue = $currentValue, $classType));
            } catch (\Throwable $th) {
                Toastr::error($th->getFile() . " " . $th->getLine() . " in " . __FUNCTION__, $th->getMessage());
            }
        }
    }
    private function eventUpdatedNotificationAndMail($previousValue, $fields, $type, $status, $classType)
    {
        if ($status) {
            try {
                $previousValue = $this->addEntityType($previousValue, 'entity_type', $type);
                $currentValue = $this->addEntityType($fields, 'entity_type', $type);
                $userCurrentId = Auth::id();
                $this->insertLogger($currentValue, $previousValue, $userCurrentId, $classType, 'update');
                event(new UpdatedDocumentEvent(
                    $previousValue = $previousValue,
                    $currentValue = $currentValue,
                    $classType = $classType,
                    $userCurrentId = $userCurrentId,
                ));
            } catch (\Throwable $th) {
                Toastr::error($th->getFile() . " " . $th->getLine() . " in " . __FUNCTION__, $th->getMessage());
            }
        }
    }
    private function getPreviousValue($fields, $item)
    {
        $previousValue = [];
        foreach ($fields as $key => $value) {
            if ($key !== 'tableNames') {
                if (isset($item->$key)) {
                    if (($item->$key) instanceof Collection) {
                        unset($previousValue[$key]);
                    } else {
                        $previousValue[$key] = $item->$key;
                    }
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
    private function insertLogger($currentValue, $previousValue,  $userId, $classType, $action = 'create')
    {
        $isLogger = false;
        switch ($action) {
            case 'update':
                $isLogger = ($previousValue['status'] !== $currentValue['status']);
                $type = 'updated_field';
                $key = 'entity_status';
                break;
            case 'create':
            default:
                $isLogger = true;
                $type = 'created_entity';
                $key = 'create';
                break;
        }
        if ($isLogger) {
            Logger::create([
                'loggable_type' => $classType,
                'loggable_id' => $currentValue['id'],
                'type' => $type,
                'key' => $key,
                'old_value' => $previousValue['status'] ?? null,
                'new_value' => $currentValue['status'] ?? null,
                'user_id' => $userId,
                'owner_id' => $userId,
                'created_at' => $currentValue['updated_at'],
            ]);
        }
    }
}

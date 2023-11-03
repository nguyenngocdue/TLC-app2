<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\CreateNewDocumentEvent;
use App\Events\UpdatedDocumentEvent;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\Logger;
use App\Utils\Support\JsonControls;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait TraitSendNotificationAndMail
{
    private function eventCreatedNotificationAndMail($fields, $id, $status, $toastrResult)
    {
        $modelPath = $this->modelPath ?? null;
        if (!$modelPath) return;
        if (($modelPath)::isStatusless()) return;
        if ($status && empty($toastrResult)) {
            $fields = $this->addEntityValue($fields, 'id', $id);
            $fields = $this->addEntityValue($fields, 'status', $status);
            try {
                $currentValue = $this->addEntityValue($fields, 'entity_type', $this->type);
                $this->insertLogger($currentValue, null, Auth::id(), ($modelPath));
                if (!($this->ignoreSendMail())) {
                    event(new CreateNewDocumentEvent($currentValue = $currentValue, $this->type, ($modelPath)));
                }
            } catch (\Throwable $th) {
                // dd($th->getMessage());
                Toastr::error($th->getFile() . " " . $th->getLine() . " in " . __FUNCTION__, $th->getMessage());
            }
        }
    }
    private function eventUpdatedNotificationAndMail($previousValue, $fields, $status, $toastrResult)
    {
        if (($this->modelPath)::isStatusless()) return;
        if ($status && empty($toastrResult)) {
            try {
                $previousValue = $this->addEntityValue($previousValue, 'entity_type', $this->type);
                $currentValue = $this->addEntityValue($fields, 'entity_type',  $this->type);
                $userCurrentId = Auth::id();
                $this->insertLogger($currentValue, $previousValue, $userCurrentId, $this->modelPath, 'update');
                if (!($this->ignoreSendMail())) {
                    event(new UpdatedDocumentEvent(
                        $previousValue = $previousValue,
                        $currentValue = $currentValue,
                        $type = $this->type,
                        $modelPath = $this->modelPath,
                        $userCurrentId = $userCurrentId,
                    ));
                }
            } catch (\Throwable $th) {
                Toastr::error($th->getFile() . " " . $th->getLine() . " in " . __FUNCTION__, $th->getMessage());
            }
        }
    }
    private function ignoreSendMail()
    {
        return LibApps::getFor($this->type)['do_not_send_notification_mails'] ?? false;
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
    private function insertLogger($currentValue, $previousValue,  $userId, $modelPath, $action = 'create')
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
            if (!isset($currentValue['id'])) Toastr::warning(
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

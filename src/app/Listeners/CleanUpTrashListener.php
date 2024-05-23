<?php

namespace App\Listeners;

use App\Utils\Support\Entities;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class CleanUpTrashListener
{
    private $day_to_keep = 30;

    private function getPluralList()
    {
        $pluralEntities = Entities::getAllPluralNames();

        $result = [];
        foreach ($pluralEntities as $entity) {
            $hasDel = Schema::hasColumn($entity, 'deleted_at');
            if (!$hasDel) {
                Log::info($entity . " does not have deleted_at column.");
                continue;
            }
            $result[] = Str::plural($entity);
        }
        return $result;
    }

    private function cleanUp($pluralList, $date)
    {
        $count = 0;
        foreach ($pluralList as $entity) {
            $modelPath = Str::modelPathFrom($entity);
            $deletedRecordCount =   $modelPath::onlyTrashed()->where('deleted_at', '<', $date)->forceDelete();
            if ($deletedRecordCount > 0) {
                Log::channel("cleanup_trash_channel")->info("CleanUpTrashListener: Deleted " . $deletedRecordCount . " records from " . $entity);
                $count += $deletedRecordCount;
            }
        }
        return $count;
    }

    public function handle($event)
    {
        $list = $this->getPluralList();
        $date = Carbon::now()->subDays($this->day_to_keep);
        $count =  $this->cleanUp($list, $date);
        Log::channel("cleanup_trash_channel")->info("CleanUpTrashListener: $count items deleted.");
    }
}

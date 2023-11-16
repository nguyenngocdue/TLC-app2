<?php

namespace App\Utils\OptimisticLocking;

use App\Events\BroadcastEvents\BroadcastCreateEditEvent;
use App\Utils\Constant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait TraitOptimisticLocking
{
    // protected static $lock = false;

    // protected static $lockDefaultColumn = false;

    // protected static $nameLockColumn = Constant::NAME_LOCK_COLUMN;

    public function updateWithOptimisticLocking($attributes)
    {
        // $this->fill($attributes);
        // if (!static::$lock) {
        //     $this->save();
        //     return true;
        // }
        // if ($this->fireModelEvent('updating') === false) {
        //     return false;
        // }
        // $dirty = $this->getDirty();
        // if (count($dirty) > 0) {
        //     $versionColumn = static::lockVersionColumn();
        //     if ($this->lockingEnabled()) {
        //         $beforeUpdateVersion = $this->currentLockVersion();
        //         $isCheckUpdate = $this->databaseLockVersion() == $beforeUpdateVersion;
        //         if ($isCheckUpdate) {
        //             $this->setAttribute($versionColumn, $newVersion = $beforeUpdateVersion + 1);
        //             $dirty[$versionColumn] = $newVersion;
        //             $this->update($dirty);
        //             broadcast(new BroadcastCreateEditEvent(auth()->user(), $attributes, $this->getTable()));
        //         } else {
        //             $this->setAttribute($versionColumn, $beforeUpdateVersion);
        //             throw new OptimisticLockingException('Model has been changed during update.');
        //         }
        //     }
        //     $this->fireModelEvent('updated', false);
        //     $this->syncChanges();
        // }
        // return true;
    }
    // protected static function lockVersionColumn()
    // {
    //     return static::$lockDefaultColumn ? Constant::NAME_LOCK_COLUMN_DEFAULT : static::$nameLockColumn;
    // }
    // public function currentLockVersion()
    // {
    //     return $this->getAttribute(static::lockVersionColumn());
    // }
    // public function databaseLockVersion()
    // {
    //     return $this->getOriginal(static::lockVersionColumn());
    // }

    // protected static function defaultLockVersion()
    // {
    //     return 1;
    // }
    // protected function lockingEnabled()
    // {
    //     return $this->lock === null ? true : $this->lock;
    // }
    // protected function disableLocking()
    // {
    //     $this->lock = false;
    //     return $this;
    // }
    // protected function enableLocking()
    // {
    //     $this->lock = true;
    //     return $this;
    // }
}

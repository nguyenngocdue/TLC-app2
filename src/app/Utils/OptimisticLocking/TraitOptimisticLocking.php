<?php

namespace App\Utils\OptimisticLocking;

use App\Utils\Constant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait TraitOptimisticLocking
{
    protected $lock = true;

    protected $lockDefaultColumn = false;

    protected $nameLockColumn = Constant::NAME_LOCK_COLUMN;

    protected static function bootOptimisticLocking()
    {
        static::creating(function (Model $model) {
            if ($model->currentLockVersion() === null) {
                $model->{static::lockVersionColumn()} = static::defaultLockVersion();
            }
        });
    }
    protected function performUpdate(Builder $queryBuilder)
    {
        if ($this->fireModelEvent('updating') === false) {
            return false;
        }

        $dirty = $this->getDirty();
        if (count($dirty) > 0) {
            $versionColumn = static::lockVersionColumn();
            $this->setKeysForSaveQuery($queryBuilder);
            if ($versionColumn === Constant::NAME_LOCK_COLUMN_DEFAULT) {
                if ($this->lockingEnabled()) {
                    $queryBuilder->where($versionColumn, $this->currentLockVersion());
                }
                $beforeUpdateVersion = $this->currentLockVersion();
            }

            if ($this->lockingEnabled()) {
                $queryBuilder->where($versionColumn, $this->currentLockVersion());
            }
            $beforeUpdateVersion = $this->currentLockVersion();
            $this->setAttribute($versionColumn, $newVersion = $beforeUpdateVersion + 1);
            $dirty[$versionColumn] = $newVersion;

            $result = $queryBuilder->update($dirty);
            if ($result === 0) {
                $this->setAttribute($versionColumn, $beforeUpdateVersion);
                throw new OptimisticLockingException('Model has been changed during update.');
            }
            $this->fireModelEvent('updated', false);
            $this->syncChanges();
        }
        return true;
    }
    protected static function lockVersionColumn()
    {
        return static::$lockDefaultColumn ? Constant::NAME_LOCK_COLUMN_DEFAULT : static::$nameLockColumn;
    }
    public function currentLockVersion()
    {
        return $this->getAttribute(static::lockVersionColumn());
    }

    protected static function defaultLockVersion()
    {
        return 1;
    }
    protected function lockingEnabled()
    {
        return $this->lock === null ? true : $this->lock;
    }
    protected function disableLocking()
    {
        $this->lock = false;
        return $this;
    }
    protected function enableLocking()
    {
        $this->lock = true;
        return $this;
    }
}

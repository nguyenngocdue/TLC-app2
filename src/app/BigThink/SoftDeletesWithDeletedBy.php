<?php

namespace App\BigThink;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait SoftDeletesWithDeletedBy
{
    use SoftDeletes;

    protected static $disableCustomSoftDelete = false;
    public static function bootCustomSoftDeletes()
    {
        static::addGlobalScope('custom_soft_delete', function (Builder $builder) {
            if (static::isCustomSoftDeleteEnabled()) {
                $builder->withTrashed();
            }
        });
    }

    public function delete()
    {
        $this->{$this->getDeletedAtColumn()} = $this->freshTimestamp();
        $this->{$this->getDeletedByColumn()} = Auth::id();
        $this->save();
    }

    public static function isCustomSoftDeleteEnabled()
    {
        return static::isCustomSoftDeleteEnabled() || !static::isCustomSoftDeleteDisabled();
    }

    public static function isCustomSoftDeleteDisabled()
    {
        return isset(static::$disableCustomSoftDelete) && static::$disableCustomSoftDelete;
    }

    public static function disableCustomSoftDelete()
    {
        static::$disableCustomSoftDelete = true;
    }

    public static function enableCustomSoftDelete()
    {
        static::$disableCustomSoftDelete = false;
    }

    public function getDeletedByColumn()
    {
        return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }
}

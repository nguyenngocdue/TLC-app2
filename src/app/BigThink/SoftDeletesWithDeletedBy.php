<?php

namespace App\BigThink;

use App\Models\Comment;
use App\Scopes\SoftDeletingScopeCustom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait SoftDeletesWithDeletedBy
{
    use SoftDeletes;



    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new SoftDeletingScopeCustom);
    }

    public function deleteById()
    {
        return Auth::id();
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function runSoftDelete()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();
        $id = $this->deleteById();
        $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];
        $columns[$this->getDeletedByColumn()] =  $id;

        $this->{$this->getDeletedAtColumn()} = $time;
        $this->{$this->getDeletedByColumn()} = $id;

        if ($this->usesTimestamps() && !is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;
            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }
        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

        $this->fireModelEvent('trashed', false);
    }
    /**
     * Restore a soft-deleted model instance.
     *
     * @return bool
     */
    public function restore()
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = null;
        $this->{$this->getDeletedByColumn()} = null;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }

    public function getDeletedByColumn()
    {
        return 'deleted_by';
        // return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }
    /**
     * Get the fully qualified "deleted at" column.
     *
     * @return string
     */
    public function getQualifiedDeletedByColumn()
    {
        return $this->qualifyColumn($this->getDeletedByColumn());
    }
}

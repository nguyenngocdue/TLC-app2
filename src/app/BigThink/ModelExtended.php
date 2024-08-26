<?php

namespace App\BigThink;

use App\Models\User;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use App\Utils\Support\Json\SuperProps;
use Database\Seeders\FieldSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

abstract class ModelExtended extends Model
{
    use Searchable;
    use Notifiable;
    use HasFactory;

    use HasStatus;
    // use HasCheckbox;
    use HasComments;
    use HasAttachments;

    use CheckPermissionEntities;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use TraitMorphManyByFieldName;

    use TraitModelExtended;
    use SoftDeletesWithDeletedBy;

    public static $eloquentParams = [];
    // public static $oracyParams = [];
    public static $statusless = false;
    public static $nameless = false;
    public static $hasDueDate = false;

    public static function isStatusless()
    {
        return static::$statusless;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        static::$eloquentParams['getOwner'] =  ["belongsTo", User::class, "owner_id"];
        static::$eloquentParams['getDeletedBy'] =  ["belongsTo", User::class, "deleted_by"];
    }

    function getOwner()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getDeletedBy()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getCurrentBicId()
    {
        $tableName = $this->getTableName();
        $sp = SuperProps::getFor($tableName);
        $statuses = $sp['statuses'];
        $result = null;
        if (isset($statuses[$this->status])) {
            $status = $statuses[$this->status];
            $assignee_1_to_9 = $status['ball-in-courts']['ball-in-court-assignee'];
            $assignee_1_to_9 = $assignee_1_to_9 == 'creator' ? 'owner_id' : $assignee_1_to_9;
            // dump($assignee_1_to_9);
            $result = $this->{$assignee_1_to_9};
        }
        return $result;
    }

    /* This method will be very slow */
    function getCurrentMonitorIds()
    {
        $tableName = $this->getTableName();
        $sp = SuperProps::getFor($tableName);
        $statuses = $sp['statuses'];
        $result = [];
        if (isset($statuses[$this->status])) {
            $status = $statuses[$this->status];
            $monitors_1_to_9 = $status['ball-in-courts']['ball-in-court-monitors'];
            $monitors_1_to_9 = $monitors_1_to_9 ? $monitors_1_to_9 : "getMonitors1";
            if (strlen($monitors_1_to_9) > 0) {
                $fn = substr($monitors_1_to_9, 0, strlen($monitors_1_to_9) - 2);
                if (method_exists($this, $fn)) $result = $this->$fn->pluck('id')->toArray();
            }
        }
        return $result;
    }

    public function getNameAttribute($value)
    {
        if ($this::$nameless) return "#" . $this->id;
        return $value;
    }

    // static $singletonDbUserCollection = null;
    // public static function getCollectionCache()
    // {
    //     if (!isset(static::$singletonDbUserCollection[static::class])) {
    //         $all = static::all();
    //         foreach ($all as $item) $indexed[$item->id] = $item;
    //         static::$singletonDbUserCollection[static::class] = collect($indexed);
    //     }
    //     return static::$singletonDbUserCollection;
    // }
    // public static function getCollection()
    // {
    //     return static::getCollectionCache()[static::class] ?? collect();
    // }
    // public static function findFromCache($id)
    // {
    //     // if(!isset(static::getCollection()[$id]))
    //     return static::getCollectionCache()[static::class][$id] ?? null;
    // }

    static $singletonDbCollection = null;
    public static function getCollectionCache($className, $id = null, $with = [])
    {
        $withStr = join("+", $with);
        $key = $className . "|" . $withStr;
        if (!isset(static::$singletonDbCollection[$key])) {
            // Log::info("Cache miss for $key");
            $all = static::query();
            if (count($with) > 0) {
                foreach ($with as $w) $all = $all->with($w);
            }
            $all = $all->get();
            foreach ($all as $item) $indexed[$item->id] = $item;
            static::$singletonDbCollection[$key] = $indexed;
        }
        if ($id == 'all') {
            return collect(static::$singletonDbCollection[$key]);
        } else {
            if (isset(static::$singletonDbCollection[$key][$id]))
                return static::$singletonDbCollection[$key][$id];
            else
                return null;
        }
    }
    public static function getCollection()
    {
        return static::getCollectionCache(static::class, "all");
    }
    public static function findFromCache($id, $with = [])
    {
        return static::getCollectionCache(static::class, $id, $with);
    }

    public static function getFirstBy($columnName, $value, $with = [])
    {
        $all = static::query()->where($columnName, $value);
        if (count($with) > 0) foreach ($with as $w) $all = $all->with($w);
        return $all->first();
    }

    private static $singletonMorphMany = [];

    public static function getCollectionMorphMany($ids, $fieldNameCategory, $modelName, $keyType, $keyId, $useTableField)
    {
        $key = $modelName . "_" . $fieldNameCategory;
        if (!isset(static::$singletonMorphMany[$key])) {
            $query = $modelName::query()->where($keyType, static::class)->whereIn($keyId, $ids);
            if ($useTableField) {
                $query->where('category', FieldSeeder::getIdFromFieldName($fieldNameCategory));
            }
            static::$singletonMorphMany[$key] = $query->get()->groupBy($keyId);
        }
    }
    /**
     * Undocumented function
     *
     * @param array $ids
     * @param string $fieldNameCategory Field name category same eloquent params function
     * @param bool $useTableField MorphMany table using table field key category
     * @return mixed|array value MorphMany
     */
    public function getMorphManyByIds($ids = [], $fieldNameCategory = null, $useTableField = true)
    {
        if ($fieldNameCategory) {
            $eloquentParams = static::$eloquentParams[$fieldNameCategory] ?? [];
            static::getCollectionMorphMany($ids, $fieldNameCategory, $eloquentParams[1], $eloquentParams[3], $eloquentParams[4], $useTableField);
            $key = $eloquentParams[1] . "_" . $fieldNameCategory;
            $result = static::$singletonMorphMany[$key][$this->id] ?? [];
            return $result;
        }
        return [];
    }
}

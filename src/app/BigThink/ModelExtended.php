<?php

namespace App\BigThink;

use App\Models\User;
use App\Utils\OptimisticLocking\TraitOptimisticLocking;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use App\Utils\Support\Json\SuperProps;
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
    use TraitOptimisticLocking;

    use HasStatus;
    use HasCheckbox;
    use HasComments;
    use HasAttachments;

    use CheckPermissionEntities;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use TraitMorphManyByFieldName;

    use TraitModelExtended;
    use SoftDeletesWithDeletedBy;

    public $eloquentParams = [];
    public $oracyParams = [];
    protected static $statusless = false;

    public static function isStatusless()
    {
        return static::$statusless;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->eloquentParams['getOwner'] =  ["belongsTo", User::class, "owner_id"];
        $this->eloquentParams['getDeletedBy'] =  ["belongsTo", User::class, "deleted_by"];
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'description' => $this->description,
            // 'slug' => $this->slug,
        ];
    }

    function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getDeletedBy()
    {
        $p = $this->eloquentParams[__FUNCTION__];
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
            $monitors_1_to_9 = $monitors_1_to_9 ? $monitors_1_to_9 : "getMonitors1()";
            if (strlen($monitors_1_to_9) > 0) {
                $fn = substr($monitors_1_to_9, 0, strlen($monitors_1_to_9) - 2);
                if (method_exists($this, $fn)) $result = $this->$fn()->pluck('id')->toArray();
            }
        }
        return $result;
    }

    public function getName()
    {
        if ($this->nameless) return "#" . $this->id;
        else return $this->name;
    }
}

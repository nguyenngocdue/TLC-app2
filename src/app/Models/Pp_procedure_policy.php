<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pp_procedure_policy extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "department_id",
        "notify_to",
        "notify_schedule",

        "owner_id",
        "deleted_by",
        "deleted_at",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getDepartment" => ['belongsTo', Department::class, 'department_id'],
        "getNotifyTo" => ['belongsTo', Term::class, 'notify_to'],
        "getNotifySchedule" => ['belongsTo', Term::class, 'notify_schedule'],

        "attachment_procedure_policy" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public function getDepartment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getNotifyTo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getNotifySchedule()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_procedure_policy()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}

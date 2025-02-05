<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pp_doc extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        // "department_id",
        "notify_to",
        "notify_schedule",
        "version_id",

        "parent_type",
        "parent_id",

        "owner_id",
        "deleted_by",
        "deleted_at",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        // "getDepartment" => ['belongsTo', Department::class, 'department_id'],
        "getNotifyTo" => ['belongsTo', Term::class, 'notify_to'],
        "getNotifySchedule" => ['belongsTo', Term::class, 'notify_schedule'],
        "getNotifyToHodExcluded" => ['belongsToMany', User::class, 'ym2m_pp_doc_user_notify_to_hod_excluded'],
        "getNotifyToMemberExcluded" => ['belongsToMany', User::class, 'ym2m_pp_doc_user_notify_to_member_excluded'],

        "attachment_procedure_policy" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getParent" => ['morphTo', Pp_doc::class, 'parent_type', 'parent_id'],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    // public function getDepartment()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

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

    public function getNotifyToHodExcluded()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getNotifyToMemberExcluded()
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

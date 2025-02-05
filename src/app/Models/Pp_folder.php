<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pp_folder extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "department_id",
        "notify_to",
        "notify_schedule",
        "version_id",

        "owner_id",
        "deleted_by",
        "deleted_at",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getPpDocs" => ['morphMany', Pp_doc::class, 'parent', 'parent_type', 'parent_id'],
    ];

    public function getPpDocs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function getDepartment()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    // public function getNotifyTo()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    // public function getNotifySchedule()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    // public function getNotifyToHodExcluded()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    // public function getNotifyToMemberExcluded()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    // public function attachment_procedure_policy()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    //     return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    // }
}

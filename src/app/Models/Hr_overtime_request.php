<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_overtime_request extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description',
        'workplace_id', 'assignee_1', 'owner_id',
        'status', 'user_team_ot_id', "total_hours",
    ];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $creator = User::findFromCache($this->owner_id);
        return "[OTR#" . $this->id . "] - " . ($creator->name ?? "Creator");
    }

    public static $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        "getUserTeamOt" => ["belongsTo", User_team_ot::class, 'user_team_ot_id'],
        'getHrOtrLines' => ['hasMany', Hr_overtime_request_line::class, 'hr_overtime_request_id'],
        // 'getHrOtrLines1' => ['hasMany', Hr_overtime_request_line::class, 'hr_overtime_request_id'],

        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserTeamOt()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getHrOtrLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function getHrOtrLines1()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function comment_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Constant;

class Hr_timesheet_worker extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'ts_date', 'team_id', 'assignee_1',
        'owner_id', 'status', 'total_hours', 'total_ot_hours',
    ];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $team = $this->getUserTeam;
        return "[" . date(Constant::FORMAT_DATE_ASIAN, strtotime($this->ts_date)) . "] - " . ($team->name ?? "");
    }

    public static $eloquentParams = [
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        'getUserTeam' => ['belongsTo', User_team_tsht::class, 'team_id'],
        "getHrTsLines" => ["hasMany", Hr_timesheet_worker_line::class, "hr_timesheet_worker_id"],

        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];


    public function getUserTeam()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getHrTsLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function comment_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}

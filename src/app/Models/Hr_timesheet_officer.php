<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Constant;

class Hr_timesheet_officer extends ModelExtended
{
    protected $fillable = ['id', 'name', 'week', 'assignee_1',  'owner_id', 'status', 'total_hours'];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $creator = User::findFromCache($this->owner_id);
        $week = "W" . date(Constant::FORMAT_WEEK, strtotime($this->week));
        return "[" . $week . "] - " . ($creator->name ?? "");
    }

    public static $eloquentParams = [
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        "getHrTsLines" => ["hasMany", Hr_timesheet_officer_line::class, "hr_timesheet_officer_id"],

        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

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

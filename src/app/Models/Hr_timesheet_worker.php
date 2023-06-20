<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Constant;

class Hr_timesheet_worker extends ModelExtended
{
    protected $fillable = ['id', 'name', 'ts_date', 'team_id', 'assignee_1',  'owner_id', 'status'];
    public $nameless = true;
    public function getName()
    {
        return "[" . date(Constant::FORMAT_DATE_ASIAN, strtotime($this->ts_date)) . "] - " . $this->getUserTeam->name;
    }

    public $eloquentParams = [
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        'getUserTeam' => ['belongsTo', User_team_ot::class, 'team_id'],
        'getHrTsLines' => ['morphMany', Hr_timesheet_line::class, 'timesheetable', 'timesheetable_type', 'timesheetable_id'],

        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];


    public function getUserTeam()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getHrTsLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function comment_rejected_reason()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_overtime_request extends ModelExtended
{
    protected $fillable = ['id', 'name', 'workplace_id', 'assignee_1',  'owner_id', 'status'];
    protected $table = "hr_overtime_requests";
    public $nameless = true;

    public $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
        "getAssignee1" => ["belongsTo", User::class, 'assignee_1'],
        'getHrOtrLines' => ['hasMany', Hr_overtime_request_line::class, 'hr_overtime_request_id'],
        'getHrOtrLines1' => ['hasMany', Hr_overtime_request_line::class, 'hr_overtime_request_id'],

        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getWorkplace()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getHrOtrLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getHrOtrLines1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function comment_rejected_reason()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}

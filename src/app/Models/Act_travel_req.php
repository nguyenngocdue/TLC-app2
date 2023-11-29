<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_req extends ModelExtended
{
    protected $fillable = [
        "id", "name", "title", "description", "status", "user_id",
        "travel_type_id", "workplace_id", "staff_discipline_id",
        "staff_workplace_id", "remark", "assignee_1", "owner_id",
        "total_travel_day", "total_travel_amount",
    ];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        return $this->title . " (" . number_format($this->total_travel_amount, 2) . " USD)";
    }

    public static $eloquentParams = [
        'getUser' => ['belongsTo', User::class, 'user_id'],
        'getTravelReqLines' => ['hasMany', Act_travel_req_line::class, 'act_travel_req_id'],
        'getTravelType' => ['belongsTo', Term::class, 'travel_type_id'],
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
        'getDiscipline' => ['belongsTo', User_discipline::class, 'staff_discipline_id'],
        'getWorkplaceStaff' => ['belongsTo', Workplace::class, 'staff_workplace_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        "attachment_current_passport" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_current_portrait" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public static $oracyParams = [
        "getReqTravelDesk()" => ["getCheckedByField", Term::class],
        "getMonitors1()" => ["getCheckedByField", User::class],
        "getMonitors2()" => ["getCheckedByField", User::class],

    ];
    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function attachment_current_passport()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_current_portrait()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getTravelReqLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTravelType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getReqTravelDesk()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkplaceStaff()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getMonitors2()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
        ];
    }
}

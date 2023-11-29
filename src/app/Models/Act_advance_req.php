<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_advance_req extends ModelExtended
{
    protected $fillable = [
        "id", "name", "title", "description", "status", "user_id",
        "radio_advance_type", "advance_amount", "currency_id",
        "advance_amount_word", "assignee_1", "owner_id"
    ];
    protected $with = ["getCurrency"];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $currency = $this->getCurrency;
        return $this->title . " (" . number_format($this->advance_amount, 2) . " " . ($currency->name ?? "") . ")";
    }

    public static $eloquentParams = [
        'getUser' => ['belongsTo', User::class, 'user_id'],
        'getCurrency' => ['belongsTo', Act_currency::class, 'currency_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        "radioAdvanceType" => ['belongsTo', Term::class, 'radio_advance_type'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        'getSubProjectsOfAdvanceReq()' => ['getCheckedByField', Sub_project::class,],

    ];
    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProjectsOfAdvanceReq()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function radioAdvanceType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'currency_xr_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'month', 'cloneable' => true],
            ['dataIndex' => 'currency_pair_id', 'cloneable' => true],
            ['dataIndex' => 'value'],
        ];
    }
}

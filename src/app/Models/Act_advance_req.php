<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_advance_req extends ModelExtended
{
    protected $fillable = ["id","name", "description", "status","staff_id","sub_project_id","radio_advance_type"
    ,"advance_amount","currency_id","advance_amount_word","assignee_1","owner_id"];
    protected $table = "act_advance_reqs";

    public static $eloquentParams = [
        'getStaff' => ['belongsTo', User::class, 'staff_id'],
        'getCurrency' => ['belongsTo', Act_currency::class, 'currency_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        "radioAdvanceType" => ['belongsTo', Term::class, 'radio_advance_type'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        'getSubProjectOfAdvanceReq()' => ['getCheckedByField', Sub_project::class,],
       
    ];
    public function getStaff()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProjectOfAdvanceReq()
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
            ['dataIndex' => 'month','cloneable' => true],
            ['dataIndex' => 'currency_pair_id', 'cloneable' => true],
            ['dataIndex' => 'value'],
        ];
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_car extends ModelExtended
{
    protected $fillable = [
        'id', "qaqc_ncr_id", "responsible_person", "remark", "cause_analysis",
        "corrective_action", "order_no", "owner_id", "status",
    ];
    public static $nameless = true;

    public static $eloquentParams = [
        "getQaqcNcr" => ['belongsTo', Qaqc_ncr::class, 'qaqc_ncr_id'],
        "getResponsiblePerson" => ['belongsTo', User::class, 'responsible_person'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getQaqcNcr()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getResponsiblePerson()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true],
            ["dataIndex" => 'id'],
            ['dataIndex' => 'qaqc_ncr_id', 'title' => 'NCR ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'cause_analysis'],
            ['dataIndex' => 'corrective_action'],
            ['dataIndex' => 'responsible_person'],
            ['dataIndex' => 'remark'],
            // ['dataIndex' => 'status'],
        ];
    }
}

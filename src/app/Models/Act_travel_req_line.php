<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_req_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "status", "from_id", "order_no", "act_travel_req_id",
        "to_id", "workplace_id", "project_id", "datetime_outbound_1", "datetime_outbound_2", "datetime_inbound_1",
        "datetime_inbound_2", "total_day", "total_price", "remark", "owner_id"
    ];
    protected $table = "act_travel_req_lines";

    public static $eloquentParams = [
        'getFrom' => ['belongsTo', Act_travel_place::class, 'from_id'],
        'getTo' => ['belongsTo', Act_travel_place::class, 'to_id'],
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
        'getProject' => ['belongsTo', Project::class, 'project_id'],
        "getParent" => ['belongsTo', Act_travel_req::class, 'act_travel_req_id'],
        // "getTravelPlacePair" => [],
    ];

    public static $oracyParams = [];
    public function getFrom()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'act_travel_req_id', 'value_as_parent_id' => true, 'invisible' => true],
            ['dataIndex' => 'from_id', 'cloneable' => true],
            ['dataIndex' => 'to_id', 'cloneable' => true],
            ['dataIndex' => 'workplace_id', 'cloneable' => true],
            ['dataIndex' => 'project_id', 'cloneable' => true],
            ['dataIndex' => 'datetime_outbound_1', 'cloneable' => true],
            ['dataIndex' => 'datetime_outbound_2', 'cloneable' => true],
            ['dataIndex' => 'datetime_inbound_1', 'cloneable' => true],
            ['dataIndex' => 'datetime_inbound_2', 'cloneable' => true],
            ['dataIndex' => 'total_day', 'footer' => "agg_sum"],
            ['dataIndex' => 'total_price', 'footer' => "agg_sum"],
            ['dataIndex' => 'remark'],
        ];
    }
}

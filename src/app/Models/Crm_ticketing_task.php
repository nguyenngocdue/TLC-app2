<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Crm_ticketing_task extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'owner_id', 'status', 'order_no',
        'priority_id', 'ticketing_id', 'due_date',
    ];
    // public static $statusless = true;

    public static $eloquentParams = [
        "getTicketing" => ['belongsTo', Crm_ticketing::class, 'ticketing_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
    ];

    public function getTicketing()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'ticketing_id', 'value_as_parent_id' => true, 'invisible' => true,],

            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'priority_id'],
            ['dataIndex' => 'due_date'],
            ['dataIndex' => 'status'],
        ];
    }
}

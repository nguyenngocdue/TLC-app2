<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ppr_routing extends ModelExtended
{
    protected $fillable = ["id","name", "description", "slug", "owner_id"];

    protected $table = 'ppr_routings';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getPprRoutingLines" => ['hasMany', Ppr_routing_line::class, 'ppr_routing_id'],
    ];

    public static $oracyParams = [
    ];

    public function getPprRoutingLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
        ];
    }
}

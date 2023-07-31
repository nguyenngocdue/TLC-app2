<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ppr_routing_link extends ModelExtended
{
    protected $fillable = ["id", "name", "parent", "description", "slug", 'owner_id'];

    protected $table = 'ppr_routing_links';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getProdRoutings" => ['belongsToMany', Prod_routing::class, 'ppr_routing_lines', 'ppr_routing_link_id', 'ppr_routing_id'],
    ];

    public function getProdRoutings()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_hours', 'target_man_hours');
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
        ];
    }
}

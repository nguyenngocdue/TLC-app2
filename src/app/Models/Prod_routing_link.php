<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing_link extends ModelExtended
{
    protected $fillable = [
        "id", "name", "parent", "description", "slug",  'owner_id',
        'prod_discipline_id', 'standard_uom_id',
    ];

    protected $table = 'prod_routing_links';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getStandardUom" => ['belongsTo', Term::class, 'standard_uom_id'],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getProdSequences" => ['hasMany', Prod_sequence::class, 'prod_routing_link_id'],
        "getProdRoutings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'prod_routing_link_id', 'prod_routing_id'],
    ];

    public function getStandardUom()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutings()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_man_power', 'target_hours', 'target_man_hours', 'target_min_uom');
    }

    public function getProdSequences()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'prod_discipline_id',],
        ];
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing_link extends ModelExtended
{
    protected $fillable = ["id", "name", "parent", "description", "slug", 'prod_discipline_id', 'owner_id'];

    protected $table = 'prod_routing_links';
    protected static $statusless = true;

    public $eloquentParams = [
        "getDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],

        "getProdSequences" => ['hasMany', Prod_sequence::class, 'prod_routing_link_id'],

        "getProdRoutings" => ['belongsToMany', Prod_routing::class, 'prod_routing_details', 'prod_routing_link_id', 'prod_routing_id'],
    ];

    public function getProdRoutings()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_hours', 'target_man_hours');
    }

    public function getProdSequences()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
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

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing_detail extends ModelExtended
{
    protected $fillable = [
        "id", "prod_routing_id", "prod_routing_link_id", "erp_routing_link_id",
        "wir_description_id", "target_hours", "target_man_hours", "priority", "owner_id"
    ];
    protected $table = "prod_routing_details";
    public $nameless = true;

    public $eloquentParams = [
        "getProdRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "getProdRoutingLink" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
        "getErpRoutingLink" => ['belongsTo', Erp_routing_link::class, 'erp_routing_link_id'],
        "getWirDescription" => ['belongsTo', Wir_description::class, 'wir_description_id'],
        "getOwner" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getProdRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutingLink()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getErpRoutingLink()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescription()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => !true],
            ['dataIndex' => 'prod_routing_id',],
            ['dataIndex' => 'prod_routing_link_id',],
            ['dataIndex' => 'erp_routing_link_id',],
            ['dataIndex' => 'wir_description_id',],
            ["dataIndex" => 'target_hours'],
            ["dataIndex" => 'target_man_hours'],
        ];
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_order extends ModelExtended
{
    protected $fillable = [
        "id", "slug", "name", "status", "production_name", "compliance_name", "description", "quantity",
        "sub_project_id", "prod_routing_id", "status", "meta_type", "meta_id", "owner_id"
    ];

    protected $table = 'prod_orders';

    public $eloquentParams = [
        "getProdSequences" => ['hasMany', Prod_sequence::class, 'prod_order_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getProdRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "getQaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'prod_order_id'],
        "getMeta" => ['morphTo', Prod_order::class, 'meta_type', 'meta_id'],
    ];

    public function getSubProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMeta()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getProdRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdSequences()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcInspChklsts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'name', 'title' => 'Priority'],
            ['dataIndex' => 'production_name'],
            ['dataIndex' => 'compliance_name'],
            ['dataIndex' => 'prod_routing_id'],
            ['dataIndex' => 'status'],
            // ['dataIndex' => 'getMeta', 'rendererParam' => "id"],
        ];
    }
}

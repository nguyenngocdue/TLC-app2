<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_order extends ModelExtended
{
    public $timestamps = true;
    protected $fillable = [
        "id", "slug", "name", "status", "production_name", "compliance_name", "description", "quantity",
        "sub_project_id", "prod_routing_id", "status", "meta_type", "meta_id", "owner_id"
    ];
    protected $primaryKey = 'id';
    protected $table = 'prod_orders';

    public $eloquentParams = [
        "prodSequences" => ['hasMany', Prod_sequence::class, 'prod_order_id'],
        "subProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "prodRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "qaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'prod_order_id'],
        "getMeta" => ['morphTo', Prod_order::class, 'meta_type', 'meta_id'],
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],

    ];

    public function subProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getMeta()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function prodRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function prodSequences()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function qaqcInspChklsts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOwnerId()
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
        ];
    }
}

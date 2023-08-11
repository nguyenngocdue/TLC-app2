<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Illuminate\Support\Facades\Log;

class Ppr_sequence extends ModelExtended
{
    protected $fillable = [
        "prod_order_id", "ppr_routing_link_id",
        "status",  "uom_id", "total_uom", "owner_id",
        //"priority", "expected_start_at", "expected_finish_at",

        "sub_project_id", "ppr_routing_id",

        "total_hours", "worker_number", "total_man_hours",
        "start_date", "end_date",
    ];

    protected $table = 'prod_sequences';
    public static $nameless = true;

    public static $eloquentParams = [
        "getProdOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        "getPprRoutingLinks" => ['belongsTo', Ppr_routing_link::class, 'ppr_routing_link_id'],
        "getUomId" => ["belongsTo", Term::class, 'uom_id'],

        "getPprRuns" => ['hasMany', Ppr_run::class, 'ppr_sequence_id'],
        "getPprRoutingDetails" => ['hasMany', Ppr_routing_line::class, "ppr_routing_link_id", "ppr_routing_link_id"],

        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getPprRouting" => ['belongsTo', Ppr_routing::class, "ppr_routing_id"],
    ];

    public function getProdOrder()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPprRoutingLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUomId()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPprRuns()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function getPprRoutingDetails()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2], $p[3]);
    // }

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPprRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPprRoutingDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3]);
        $prodOrder = $this->getProdOrder;
        if ($prodOrder) {
            $ppr_routing_id = $prodOrder->ppr_routing_id;
            $sql = $relation
                ->getQuery()
                ->where('ppr_routing_id', $ppr_routing_id)
                ->toSql();
            // Log::info($sql);
            return $relation;
        }
        return $relation;
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id",], //"renderer" => "id", "type" => "prod_sequences", "align" => "center"],
            ["dataIndex" => "prod_order_id", "title" => "Prod Order Id", "rendererParam" => "id"],
            ["dataIndex" => "prod_order_id", "title" => "Routing Id (*)", "rendererParam" => "prod_routing_id"],
            ["dataIndex" => "ppr_routing_link_id", "title" => "PPR Routing ID", "rendererParam" => "id"],
            ["dataIndex" => "ppr_routing_link_id",  "title" => "PPR Routing Name (*)", "rendererParam" => "name"],

            // ["dataIndex" => "expected_start_at",],
            // ["dataIndex" => "expected_finish_at",],

            ["dataIndex" => "total_hours",],
            ["dataIndex" => "total_man_hours", "title" => "Total ManHours",],
            ["dataIndex" => "ppr_routing_link_id", "title" => "Target Hours", "rendererParam" => "target_hours"],
            ["dataIndex" => "ppr_routing_link_id", "title" => "Target ManHours (*)", "rendererParam" => "target_man_hours"],
            ["dataIndex" => "status",],
        ];
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasStatus;
use Illuminate\Support\Facades\Log;

class Prod_run extends ModelExtended
{
    use HasStatus;
    public $timestamps = true;
    protected $fillable = ["prod_order_id", "prod_routing_link_id", "status", "total_hours", "total_man_hours"];
    protected $primaryKey = 'id';
    protected $table = 'prod_runs';
    public $nameless = true;

    public $eloquentParams = [
        "prodOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        "prodRunLines" => ['hasMany', Prod_run_line::class, 'prod_run_id'],
        "prodRoutingLinks" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
        "prodRoutingDetails" => ['hasMany', Prod_routing_detail::class, "prod_routing_link_id", "prod_routing_link_id"],
    ];

    public function prodOrder()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function prodRoutingLinks()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function prodRunLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function prodRoutingDetails()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2], $p[3]);
    // }

    public function prodRoutingDetails()
    {
        $prodOrder = $this->prodOrder;
        $prod_routing_id = $prodOrder->prod_routing_id;
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3]);
        $sql = $relation
            ->getQuery()
            ->where('prod_routing_id', $prod_routing_id)
            ->toSql();
        // Log::info($sql);
        return $relation;
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id", "renderer" => "id", "type" => "prod_runs", "align" => "center"],
            ["dataIndex" => "prodOrder", "title" => "Prod Order Id", "renderer" => "column", "rendererParam" => "id"],
            ["dataIndex" => "prodOrder", "title" => "Routing Id", "renderer" => "column", "rendererParam" => "prod_routing_id"],
            ["dataIndex" => "prodRoutingLinks", "renderer" => "column", "rendererParam" => "id"],
            ["dataIndex" => "prodRoutingLinks", "renderer" => "column", "rendererParam" => "name"],

            ["dataIndex" => "total_hours", "align" => "right"],
            ["dataIndex" => "total_man_hours", "title" => "Total ManHours", "align" => "right"],
            ["dataIndex" => "prodRoutingDetails", "title" => "Target Hours", "align" => "right", "renderer" => "column", "rendererParam" => "target_hours"],
            ["dataIndex" => "prodRoutingDetails", "title" => "Target ManHours", "align" => "right", "renderer" => "column", "rendererParam" => "target_man_hours"],
            ["dataIndex" => "status", "renderer" => "status", "align" => "center"],
        ];
    }
}

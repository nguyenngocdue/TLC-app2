<?php

namespace App\Models;

use App\Http\Traits\HasStatus;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;


class Prod_run extends Model
{
    use HasStatus, Notifiable, HasFactory, Searchable, CheckPermissionEntities;
    public $timestamps = true;
    protected $fillable = ["prod_order_id", "prod_routing_link_id", "status", "total_hours", "total_man_hours"];
    protected $primaryKey = 'id';
    protected $table = 'prod_runs';
    public $nameless = true;

    public $eloquentParams = [
        "prodOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        "prodRunLines" => ['hasMany', Prod_run_line::class, 'prod_run_id'],
        "prodRoutingLinks" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
        "prodRoutingDetails" => ['hasMany', Prod_routing_detail::class, "prod_routing_id", "prod_routing_id"],
        "prodRouting" => ['hasOne', Prod_order::class, "id",],
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
    public function prodRoutingDetails()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function prodRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id", "renderer" => "id", "type" => "prod_runs", "align" => "center"],
            ["dataIndex" => "prodOrder", "title" => "Prod Order Id", "renderer" => "column", "rendererParam" => "id"],
            ["dataIndex" => "prodRouting", "title" => "Routing Id", "renderer" => "column", "rendererParam" => "id"],
            ["dataIndex" => "prodRoutingLinks", "renderer" => "column", "rendererParam" => "id"],
            [
                "dataIndex" => "prodRoutingDetails", "align" => "right", "renderer" => "pivot2",
                "rendererParam" => [
                    "column" => 'target_hours',
                    "conditions" => [
                        ["prodRouting.id", "=", "prod_routing_id"],
                        ["prod_routing_link_id", "=", "prod_routing_link_id"],
                    ],
                ],
            ],
            [
                "dataIndex" => "prodRoutingDetails", "align" => "right", "renderer" => "pivot2",
                "rendererParam" => [
                    "column" => "target_man_hours",
                    "conditions" => [
                        ["prodRouting.id", "=", "prod_routing_id"],
                        ["prod_routing_link_id", "=", "prod_routing_link_id"],
                    ],
                ],
            ],
            ["dataIndex" => "total_hours", "align" => "right"],
            ["dataIndex" => "total_man_hours", "align" => "right"],
            ["dataIndex" => "status", "renderer" => "status", "align" => "center"],
        ];
    }
}

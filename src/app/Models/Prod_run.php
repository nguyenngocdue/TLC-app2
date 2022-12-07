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
    // protected $with = [
    // "productionOrder",
    // "productionRunLines",
    // "routingLinks",
    // ];

    public $eloquentParams = [
        "prodOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        "prodRunLines" => ['hasMany', Prod_run_line::class, 'prod_run_id'],
        "prodRoutingLinks" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
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

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id", "renderer" => "id", "type" => "prod_runs"],
            ["dataIndex" => "prodRoutingLinks", "renderer" => "column", "rendererParam" => "name"],
            ["dataIndex" => "total_hours", "align" => "right"],
            ["dataIndex" => "total_man_hours", "align" => "right"],
            ["dataIndex" => "status", "renderer" => "tag"],
        ];
    }
}

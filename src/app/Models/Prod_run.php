<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_run extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    public $timestamps = true;
    protected $fillable = ["prod_order_id", "prod_routing_link_id", "status_prod", "total_hours", "total_man_hours"];
    protected $primaryKey = 'id';
    protected $table = 'prod_runs';

    public $eloquentParams = [
        "productionOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        "productionRunLines" => ['hasMany', Prod_line::class, 'prod_run_id'],
        "routingLinks" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
    ];
    public function productionOrder()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function routingLinks()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function productionRunLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

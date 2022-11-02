<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_routing extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;

    public $timestamps = true;
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'prod_routings';
    protected $with = [
        // "routingLinks",
        // "productionOrders",
    ];

    public $eloquentParams = [
        "routingLinks" => ['belongsToMany', Prod_routing_link::class, 'prod_routing_details', 'routing_id', 'routing_link_id'],
        "productionOrders" => ['hasMany', Prod_order::class],
    ];
    public function routingLinks()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_hours', 'target_man_hours');
    }

    public function productionOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
}

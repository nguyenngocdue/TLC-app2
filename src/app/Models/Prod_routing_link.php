<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_routing_link extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;

    protected $fillable = ["id", "name", "parent", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'prod_routing_links';


    public $eloquentParams = [
        "routings" => ['belongsToMany', Prod_routing::class, 'routing_details', 'prod_routing_link_id', 'prod_routing_id'],
        "productionRun" => ['hasMany', Prod_run::class, 'prod_routing_link_id'],
        "discipline" => ['belongsTo', Prod_discipline::class, 'parent'],
    ];
    public function routings()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function productionRun()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function discipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

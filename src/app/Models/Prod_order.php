<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Prod_order extends Model
{
    use HasFactory, Searchable, CheckPermissionEntities;
    public $timestamps = true;
    protected $fillable = ["id", "slug", "production_name", "production", "compliance", "description", "quantity", "prod_sub_project_id", "prod_routing_id"];
    protected $primaryKey = 'id';
    protected $table = 'prod_orders';
    protected $with = [
        "subProject",
        "routing",
        "productionRuns",
    ];

    public $eloquentParams = [
        "productionRuns" => ['hasMany', Prod_run::class, 'prod_order_id'],
        "subProject" => ['belongsTo', Sub_project::class],
        "routing" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
    ];
    public function subProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function routing()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function productionRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

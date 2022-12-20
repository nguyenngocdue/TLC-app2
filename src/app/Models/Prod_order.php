<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Http\Traits\HasStatus;

class Prod_order extends ModelExtended
{
    use HasStatus;
    public $timestamps = true;
    protected $fillable = [
        "id", "slug", "name", "status", "production", "compliance", "description", "quantity",
        "sub_project_id", "prod_routing_id", "status"
    ];
    protected $primaryKey = 'id';
    protected $table = 'prod_orders';

    public $eloquentParams = [
        "prodRuns" => ['hasMany', Prod_run::class, 'prod_order_id'],
        "subProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "prodRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "qaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'prod_order_id'],
    ];

    public function subProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function prodRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function prodRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function qaqcInspChklsts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

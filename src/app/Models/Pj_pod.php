<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_pod extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "pj_module_id", "pj_pod_type_id"];
    protected $primaryKey = 'id';
    protected $table = 'pj_pods';

    public $eloquentParams = [
        'getPjModule' => ['belongsTo', Pj_module::class, 'pj_module_id'],
        "getProdOrders" => ['morphMany', Prod_order::class, 'meta', 'meta_type', 'meta_id'],
        'getPjPodType' => ['belongsTo', Term::class, 'pj_pod_type_id'],
    ];

    public function getPjModule()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjPodType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProdOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2],$p[3],$p[4]);
    }
}

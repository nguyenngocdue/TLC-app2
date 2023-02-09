<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_shipment extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'pj_shipments';

    public $eloquentParams = [
        "getPjModules" => ['hasMany', Pj_module::class, "pj_shipment_id"],
    ];

    public function getPjModules()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

}

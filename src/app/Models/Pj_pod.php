<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_pod extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "pj_module_id", "pj_pod_type_id", "pj_shipment_id", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        'getPjModule' => ['belongsTo', Pj_module::class, 'pj_module_id'],
        'getPjPodType' => ['belongsTo', Term::class, 'pj_pod_type_id'],
        'getPjShipment' => ['belongsTo', Pj_shipment::class, 'pj_shipment_id'],

        "getProdOrders" => ['morphMany', Prod_order::class, 'meta', 'meta_type', 'meta_id'],
    ];

    public function getPjModule()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjPodType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getProdOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
    public function getPjShipment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

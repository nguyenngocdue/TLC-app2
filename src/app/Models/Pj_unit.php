<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_unit extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "pj_sell_type_id", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        'getPjSellType' => ['belongsTo', Term::class, 'pj_sell_type_id'],

        "getPjModules" => ['hasMany', Pj_module::class, "pj_unit_id"],
        "getProdOrders" => ['morphMany', Prod_order::class, 'meta', 'meta_type', 'meta_id'],
    ];

    public function getPjSellType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjModules()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}

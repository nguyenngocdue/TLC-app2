<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_unit extends ModelExtended
{
    public $timestamps = false;
    protected $fillable = ["id", "name", "description", "slug", "pj_sell_type_id", "owner_id"];

    protected $table = 'pj_units';

    public $eloquentParams = [
        'getPjSellType' => ['belongsTo', Term::class, 'pj_sell_type_id'],
        "getPjModules" => ['hasMany', Pj_module::class, "pj_unit_id"],
    ];

    public function getPjSellType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPjModules()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

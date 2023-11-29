<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_discipline_2 extends ModelExtended
{
    public $fillable = ["id", "name", "description", "slug", "prod_discipline_1", "owner_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getDiscipline1" => ['belongsTo', Prod_discipline_1::class, 'prod_discipline_1_id'],
    ];

    public function getDiscipline1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

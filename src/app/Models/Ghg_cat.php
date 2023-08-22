<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ghg_cat extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "owner_id", 'scope_id'];

    public static $eloquentParams = [
        "getScope" =>   ['belongsTo', Term::class, 'scope_id'],
    ];

    public function getScope()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

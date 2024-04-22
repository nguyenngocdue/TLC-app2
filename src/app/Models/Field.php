<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Field extends ModelExtended
{
    protected $fillable = ['id', 'name', 'reversed_name', 'description', 'owner_id'];
    public static $statusless = true;

    public static $eloquentParams = [
        'getTerms' => ['hasMany', Term::class, 'field_id'],
    ];

    public function getTerms()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

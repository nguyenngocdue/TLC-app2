<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_leave_cat extends ModelExtended
{
    protected $fillable = [
        "id", "name", "owner_id",
        "leave_days", "workplace_id", "remark",
    ];
    // public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

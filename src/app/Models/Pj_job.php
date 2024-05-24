<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_job extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    public static $statusless = true;

    public static $eloquentParams = [
        "getLines" => ["hasMany", Pj_job_line::class, "job_id"],
    ];

    public static $oracyParams = [];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

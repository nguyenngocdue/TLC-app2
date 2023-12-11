<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Department_skill_group extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "name", "description",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getSkills" => ["hasMany", Department_skill::class, "department_skill_group_id"],
    ];

    public static $oracyParams = [];

    public function getSkills()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

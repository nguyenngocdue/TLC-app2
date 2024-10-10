<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_drawing_str_type extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        // "project_id",
        "description",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        // 'getProject' => ['belongsTo', Project::class, 'project_id'],
        "getModules" => ['hasMany', Pj_module::class, 'drawing_str_type_id'],
    ];

    public function getModules()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

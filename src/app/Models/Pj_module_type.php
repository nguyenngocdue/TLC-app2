<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_module_type extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getModules" => ['hasMany', Pj_module::class, 'pj_module_type_id'],
        "getRoomList" => ["belongsToMany", Term::class, "ym2m_pj_module_type_term_get_room_list"],
    ];

    public function getModules()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRoomList()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

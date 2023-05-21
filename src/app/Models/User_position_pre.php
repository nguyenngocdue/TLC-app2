<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position_pre extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'user_position_pres';

    public $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'position_prefix', 'id'],
    ];

    public function getUsers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_type extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'user_types';

    public $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'user_type', 'id'],
    ];

    public function getUsers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

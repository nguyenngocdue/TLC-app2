<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_time_keep_type extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'user_time_keep_types';

    public $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'time_keeping_type', 'id'],
    ];

    public function getUsers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

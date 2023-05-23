<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position2 extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];

    protected $table = 'user_position2s';
    protected static $statusless = true;

    public $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'position_2', 'id'],
    ];

    public function getUsers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

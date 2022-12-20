<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_position2 extends ModelExtended
{
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'user_position2s';

    public $eloquentParams = [
        "user" => ['hasMany', User::class, 'position_2', 'id'],
    ];

    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

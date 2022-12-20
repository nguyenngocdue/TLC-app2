<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_discipline extends ModelExtended
{
    protected $fillable = ["name", "description", "def_assignee", "def_monitors", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'user_disciplines';

    public $eloquentParams = [
        "user" => ['hasMany', User::class, 'discipline', 'id'],
    ];

    public function user()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

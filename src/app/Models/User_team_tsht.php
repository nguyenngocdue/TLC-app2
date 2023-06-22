<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_tsht extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id"];

    protected $table = 'user_team_tshts';

    public $eloquentParams = [];

    public $oracyParams = [
        "getTshtMembers()" => ["getCheckedByField", User::class],
    ];

    public function getTshtMembers()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_ot extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id"];

    protected $table = 'user_team_ots';

    public $eloquentParams = [];

    public $oracyParams = [
        "getOtMembers()" => ["getCheckedByField", User::class],
    ];

    public function getOtMembers()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}

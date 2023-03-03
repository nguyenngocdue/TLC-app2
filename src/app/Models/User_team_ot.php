<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_team_ot extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "status", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'user_team_ots';

    public $eloquentParams = [
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
    ];

    public $oracyParams = [
        "getOtMembers()" => ["getCheckedByField", User::class],
    ];

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }  
    public function getOtMembers()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}

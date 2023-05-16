<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_company extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "owner_id"];
    protected $table = 'user_companies';

    public $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'company'],

        "getOwner" => ['belongsTo', User::class, 'owner_id'],
        "getDeleteBy" => ['belongsTo', User::class, 'deleted_by'],
    ];

    public function getUsers()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

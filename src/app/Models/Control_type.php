<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Control_type extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'slug', 'owner_id'];
    protected $table = "control_types";

    public $eloquentParams = [
        "getOwnerId" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}


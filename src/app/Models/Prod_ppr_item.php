<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_ppr_item extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id",

    ];

    public static $statusless = true;

    public static $eloquentParams = [
        // "getProdSequence" => ['belongsTo', Prod_sequence::class, 'prod_sequence_id'],
        // "getUsers" => ['belongsToMany', User::class, 'prod_user_runs', 'prod_run_id', 'user_id'],
    ];

    public static $oracyParams = [];
}

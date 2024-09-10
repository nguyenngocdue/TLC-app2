<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_tso_archive_task extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',

        'status',
        'owner_id',
    ];

    public static $statusless = true;

    public static $eloquentParams = [];
}

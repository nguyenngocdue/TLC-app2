<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pdf_file extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "finger_print",
        "content",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        // "getUsers" => ['hasMany', User::class, 'user_type'],
    ];

    // public function getUsers()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
}

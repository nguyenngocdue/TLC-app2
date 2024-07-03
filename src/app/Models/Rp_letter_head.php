<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_letter_head extends ModelExtended
{
    protected $fillable = ["id", "name", "content", "owner_id"];

    public static $eloquentParams = [
        "getPages" => ["hasMany", Rp_page::class, "letter_head_id"],
    ];

    public function getPages()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

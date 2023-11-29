<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Logger extends ModelExtended
{
    protected $fillable = ["loggable_type", "loggable_id", "type", "key", "old_value", "old_text", "new_value", "new_text", "user_id", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "loggable" => ['morphTo', Logger::class, 'loggable_type', 'loggable_id'],
    ];
    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function loggable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
}

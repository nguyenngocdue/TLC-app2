<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_leave_tpto extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "workplace_id", "remark",  "order_no",
        "remark", "year",
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
        "getLines" => ["hasMany", Hr_leave_tpto_line::class, "parent_id"],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

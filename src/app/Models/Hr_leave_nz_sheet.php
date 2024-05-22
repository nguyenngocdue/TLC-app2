<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_leave_nz_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id",
        "leave_days",
        "workplace_id", "user_id", "remark",
        "status",
    ];
    public static $nameless = true;
    // public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],

        'getHrLeaveLines' => ['morphMany', Hr_leave_line::class, 'leaveable', 'leaveable_type', 'leaveable_id'],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getHrLeaveLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

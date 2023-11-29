<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Site_daily_assignment extends ModelExtended
{
    protected $fillable = ["id", "site_team_id", "site_date", "owner_id", "status"];

    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getUserTeamSite" => ["belongsTo", User_team_site::class, 'site_team_id'],
        'getLines' => ['hasMany', Site_daily_assignment_line::class, 'site_daily_assignment_id'],
    ];

    public function getUserTeamSite()
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

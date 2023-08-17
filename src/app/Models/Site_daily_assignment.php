<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Site_daily_assignment extends ModelExtended
{
    protected $fillable = ["id", "site_team_id", "site_date", "owner_id", "status"];

    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getUserTeamSite" => ["belongsTo", User_team_tsht::class, 'site_team_id'],
    ];

    public function getUserTeamSite()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

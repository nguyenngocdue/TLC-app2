<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_sign_in_history extends ModelExtended
{
    protected $fillable = ["name", "description", "ip_address", "city_name", "country_name", "owner_id", "time", "browser", "browser_version", "platform", "device"];
    public static $statusless = true;

    public static $eloquentParams = [];
}

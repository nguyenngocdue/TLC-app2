<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Rp_filter_mode extends ModelExtended
{
    protected $fillable = ["id", "name", "report_id", "owner_id"];
}

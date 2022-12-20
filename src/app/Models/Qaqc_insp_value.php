<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_value extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug"];
    protected $table = "qaqc_insp_values";

    public $eloquentParams = [];
}

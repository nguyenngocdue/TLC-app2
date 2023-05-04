<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type extends ModelExtended
{
    protected $fillable = ["name", "description"];
    protected $table = "esg_metric_types";
}

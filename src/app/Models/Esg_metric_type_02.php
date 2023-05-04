<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Esg_metric_type_02 extends ModelExtended
{
    protected $fillable = ["name", "description", "esg_metric_type_id"];
    protected $table = "esg_metric_type_02s";
}

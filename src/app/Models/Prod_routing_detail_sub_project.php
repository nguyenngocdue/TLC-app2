<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing_detail_sub_project extends ModelExtended
{
    protected $fillable = [
        'owner_id',
        'sub_project_id', 'prod_routing_detail_id', 'prod_routing_id', 'prod_routing_link_id',
        "sheet_count", "avg_man_power", "avg_total_uom", "avg_min", "avg_min_uom",
    ];

    public static $eloquentParams = [];
}

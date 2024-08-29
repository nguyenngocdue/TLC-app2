<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_vat_product_posting_group extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "value",
        "owner_id",
        // "order_no",

    ];

    public static $statusless = true;

    public static $eloquentParams = [];
}

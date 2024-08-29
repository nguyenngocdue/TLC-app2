<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_type extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "owner_id",
        // "order_no",

    ];

    public static $statusless = true;

    public static $eloquentParams = [];
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_item extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "name_vi",
        "description",
        "owner_id",

        "expense_type_id",
        "location_id",
        // "order_no",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getLocation" => ["belongsTo", Term::class, "location_id"],
        "getExpenseType" => ["belongsTo", Fin_expense_type::class, "expense_type_id"],
    ];

    public function getExpenseType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLocation()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

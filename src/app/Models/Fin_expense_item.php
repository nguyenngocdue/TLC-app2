<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_item extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "owner_id",

        "gl_account_id",
        "debit_group_id",

        "expense_type_id",
        "expense_location_id",
        // "order_no",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getGlAccount" => ["belongsTo", Fin_gl_account::class, "gl_account_id"],
        "getDebitGroup" => ["belongsTo", Fin_debit_group::class, "debit_group_id"],
        "getExpenseLocation" => ["belongsTo", Term::class, "expense_location_id"],
        "getExpenseType" => ["belongsTo", Fin_expense_type::class, "expense_type_id"],
    ];

    public function getGlAccount()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDebitGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getExpenseType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getExpenseLocation()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}

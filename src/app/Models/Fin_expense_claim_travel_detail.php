<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_claim_travel_detail extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "diginet_business_trip_line_finger_print",
        "fin_expense_claim_id",

        "travel_date",
        "day_count",
        "travel_reason",

        "employee_id",
        "user_id",

        "owner_id",
        "order_no",
    ];

    public static $statusless = true;
    public static $nameless = true;
    // public function getNameAttribute($value)
    // {
    //     return "Ahihi";
    // }

    public static $eloquentParams = [
        "getUser" => ["belongsTo", User::class, "user_id"],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'fin_expense_claim_id', 'value_as_parent_id' => true,  'invisible' => true],

            ['dataIndex' => 'employee_id',],
            ['dataIndex' => 'user_id',],
            ['dataIndex' => 'diginet_business_trip_line_finger_print', 'read_only_rr2' => true, 'invisible' => true],
            ['dataIndex' => 'travel_date', 'read_only_rr2' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'travel_reason', 'read_only_rr2' => true,],
            ['dataIndex' => 'day_count', 'read_only_rr2' => true, 'footer' => 'agg_sum'],

        ];
    }
}

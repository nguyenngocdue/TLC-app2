<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_claim_adv_detail extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "act_advance_req_id",
        "fin_expense_claim_id",

        "adv_date",
        "adv_amount",
        "adv_currency_id",
        "adv_reason",

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
        "getCurrency" => ["belongsTo", Act_currency::class, "adv_currency_id"],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCurrency()
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
            ['dataIndex' => 'act_advance_req_id', 'read_only_rr2' => true, 'invisible' => true],
            ['dataIndex' => 'adv_date', 'read_only_rr2' => true, 'footer' => 'agg_none'],
            ['dataIndex' => 'adv_reason', 'read_only_rr2' => true,],
            ['dataIndex' => 'adv_amount', 'read_only_rr2' => true, 'footer' => 'agg_sum'],
            ['dataIndex' => 'adv_currency_id', 'read_only_rr2' => true,],
        ];
    }
}

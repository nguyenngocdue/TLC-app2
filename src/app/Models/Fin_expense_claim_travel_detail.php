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
        "owner_id",
        "order_no",
    ];

    public static $statusless = true;
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        return "Ahihi";
    }

    public static $eloquentParams = [];

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'diginet_business_trip_line_finger_print'],
            ['dataIndex' => 'fin_expense_claim_id'],
            ['dataIndex' => 'owner_id'],
        ];
    }
}

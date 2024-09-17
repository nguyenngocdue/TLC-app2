<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_business_trip_line extends ModelExtended
{
    protected $fillable = [
        "id",
        "finger_print",
        "employeeid",
        "employee_name",
        "company_code",
        "workplace_code",
        "tb_type",
        "tb_date",
        "number_of_tb_day",
        "tb_project",
        "tb_reason",
        "tb_document_id",
        "approver_id",
        "approver_name",
        "order_no",
        "owner_id",
    ];

    public static $statusless = true;
    public static $nameless = true;

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'employeeid',],
            ['dataIndex' => 'employee_name',],
            ['dataIndex' => 'tb_date',],
            ['dataIndex' => 'number_of_tb_day',],
            ['dataIndex' => 'tb_reason',],
        ];
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_employee_leave_sheet extends ModelExtended
{
    protected $fillable = [
        "id",
        "tb_document_id",
        "tb_type",
        "employeeid",
        "employee_name",
        "company_code",
        "workplace_code",
        "from_date",
        "to_date",
        "total_of_la_day",
        "la_reason",
        "la_note",
        "la_document_status",
        "approver_id",
        "approver_name",
        "order_no",
        "owner_id",
    ];

    public static $statusless = true;

    // public function getFillable()
    // {
    //     return $this->fillable;
    // }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_business_trip_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "tb_document_id", "employeeid", "employee_name", "company_code", "workplace_code",
        "from_date", "to_date", "tb_type", "total_of_tb_day", "tb_project", "tb_reason", "tb_note",
        "tb_document_status", "approver_id", "approver_name", "order_no", "owner_id",
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}

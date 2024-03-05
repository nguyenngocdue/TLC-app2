<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_business_trip_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "employeeid", "employee_name", "company_code", "workplace_code",
        "tb_type", "tb_date", "number_of_tb_day", "tb_project", "tb_reason", "tb_document_id", "approver_id", "approver_name", "order_no", "owner_id",
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}

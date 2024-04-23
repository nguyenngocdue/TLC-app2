<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_business_trip_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "tb_document_id", "employeeid", "employee_name", "company_code", "workplace_code",
        "tb_type", "tb_date", "tb_project",
        "approver_id", "approver_name", "order_no", "owner_id",
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}

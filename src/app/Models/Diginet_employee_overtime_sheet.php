<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_employee_overtime_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "tb_document_id", "employeeid", "employee_name", "company_code", "workplace_code",
        "ot_date", "la_type",
        "ot_hours", "ot_projects", "ot_reason", "approver_id", "approver_name", "order_no", "owner_id",
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}

<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_employee_leave_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "tb_document_id", "employeeid", "employee_name", "company_code", "workplace_code",
        "la_type", "la_date", "la_projects",
        "approver_id", "approver_name", "order_no", "owner_id",
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}

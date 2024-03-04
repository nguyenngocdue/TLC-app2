<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_employee_leave_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "employeeid", "employee_name", "company_code", "workplace_code",
        "la_type", "la_date", "number_of_la_day", "la_reason", "la_document_id", "approver_id", "approver_name", "order_no", "owner_id",
    ];
    protected $table = "diginet_employee_leave_sheets";


    public function getFillable()
    {
        return $this->fillable;
    }
}

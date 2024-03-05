<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_employee_overtime_sheet extends ModelExtended
{
    protected $fillable = [
        "id", "employeeid", "company_code", "workplace_code", "employee_name",
        "ot_date", "ot_hours", "order_no", "owner_id",
    ];
    protected $table = "diginet_employee_overtime_sheets";


    public function getFillable()
    {
        return $this->fillable;
    }
}

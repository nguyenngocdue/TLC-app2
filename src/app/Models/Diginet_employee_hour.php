<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Diginet_employee_hour extends ModelExtended
{
    protected $fillable = [
        "id",
        "employeeid",
        "employee_name",
        "company_code",
        "workplace_code",
        "date",
        "standard_hours",
        "actual_working_hours",
        "ot_hours",
        "la_hours",
        "business_trip_hours",
        "work_from_home_hours",
        "order_no",
        "owner_id",
    ];

    public static $statusless = true;

    // public function getFillable()
    // {
    //     return $this->fillable;
    // }
}

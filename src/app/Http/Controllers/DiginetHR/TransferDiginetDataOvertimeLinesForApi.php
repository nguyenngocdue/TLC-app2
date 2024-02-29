<?php

namespace App\Http\Controllers\DiginetHR;

class TransferDiginetDataOvertimeLinesForApi extends ParentTransferDiginetDataForApi
{
    public function __construct()
    {
        $endpointNameDiginet = "employee-overtime";
        $conFieldName = "ot_date";
        $modelName = "Diginet_employee_overtime_line";
        $indexData = 1;
        parent::__construct($endpointNameDiginet, $conFieldName, $modelName, $indexData);
    }
}

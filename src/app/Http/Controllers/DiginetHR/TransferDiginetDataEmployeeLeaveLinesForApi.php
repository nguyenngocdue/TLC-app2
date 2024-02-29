<?php

namespace App\Http\Controllers\DiginetHR;


class TransferDiginetDataEmployeeLeaveLinesForApi extends ParentTransferDiginetDataForApi
{
    public function __construct()
    {
        $endpointNameDiginet = "employee-leave";
        $conFieldName = "la_date";
        $modelName = "Diginet_employee_leave_line";
        $indexData = 1;
        parent::__construct($endpointNameDiginet, $conFieldName, $modelName, $indexData);
    }
}

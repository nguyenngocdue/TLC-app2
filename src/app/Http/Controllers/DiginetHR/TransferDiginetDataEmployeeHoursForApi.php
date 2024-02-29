<?php

namespace App\Http\Controllers\DiginetHR;

class TransferDiginetDataEmployeeHoursForApi extends ParentTransferDiginetDataForApi
{

    public function __construct()
    {
        $endpointNameDiginet = "employee-hours";
        $conFieldName = "date";
        $modelName = "Diginet_employee_hours";
        $indexData = null;

        parent::__construct($endpointNameDiginet, $conFieldName, $modelName, $indexData);
    }
}

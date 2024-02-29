<?php

namespace App\Http\Controllers\DiginetHR;


class TransferDiginetBusinessTripLinesForApi extends ParentTransferDiginetDataForApi
{
    public function __construct()
    {
        $endpointNameDiginet = "business-trip";
        $conFieldName = "tb_date";
        $modelName = "Diginet_business_trip_line";
        $indexData = 1;
        parent::__construct($endpointNameDiginet, $conFieldName, $modelName, $indexData);
    }
}

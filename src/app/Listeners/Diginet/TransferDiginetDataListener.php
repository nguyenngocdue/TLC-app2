<?php

namespace App\Listeners\Diginet;

use App\Http\Controllers\DiginetHR\TraitUpdateDiginetData;
use App\Utils\Support\DateFormat;
use Illuminate\Support\Facades\Log;

class TransferDiginetDataListener
{
    use TraitUpdateDiginetData;
    public function handle($event)
    {
        $periodDate = DateFormat::getPeriodMonth();
        $params = [
            'FromDate' => $periodDate['first_date'],
            'ToDate' =>  $periodDate['last_date'],
            'CompanyCode' => 'TLCM,TLCE',
            'WorkplaceCode' => 'HO,TF1,TF2,TF3,NZ,1HO,1TF1,1TF2,1TF3,1NZ',
        ];
        $info = "Data fetching time: {$params['FromDate']} - {$params['ToDate']}\nCompany Code: {$params["CompanyCode"]}\nWorkplace Code: {$params["WorkplaceCode"]}";
        Log::info("TransferDiginetDataListener: " . $info);
        $actions = $this->updateDiginetData($params);
        return $actions;
    }
}

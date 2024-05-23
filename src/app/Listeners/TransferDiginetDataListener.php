<?php

namespace App\Listeners;

use App\Http\Controllers\DiginetHR\TraitUpdateDiginetData;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateFormat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class TransferDiginetDataListener
{
    use TraitUpdateDiginetData;
    public function handle($event)
    {
        $periodDate = DateFormat::getPeriodMonth();
        $params = [
            'FromDate' => $periodDate['first_date'],
            'ToDate' =>  $periodDate['last_date'],
            'CompanyCode' => 'TLCM',
            'WorkplaceCode' => 'HO,TF1,TF2,TF3,NZ,WS',
        ];
        $info = "Data fetching time: {$params['FromDate']} - {$params['ToDate']}\nCompany Code: {$params["CompanyCode"]}\nWorkplace Code: {$params["WorkplaceCode"]}";
        Log::info("TransferDiginetDataListener: " . $info);
        $actions = $this->updateDiginetData($params);
        return $actions;
    }
}

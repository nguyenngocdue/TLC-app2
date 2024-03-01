<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiginetDataController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    private function getDataRender()
    {
        return [
            [
                "endpoint_name_diginet" => "employee-hours",
                "card_name" => "Employee Hours",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-hours.index",
            ],
            // [
            //     "endpoint_name_diginet" => "employee-leave",
            //     "card_name" => "Employee Leave",
            //     "card_description" => "This is a short description of the item.",
            //     "card_save_button_route" => "employee-leave.index",
            // ],
            [
                "endpoint_name_diginet" => "employee-leave",
                "card_name" => "Employee Leave Lines",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-leave-lines.index",
            ],
            // [
            //     "endpoint_name_diginet" => "employee-overtime",
            //     "card_name" => "Employee Overtime",
            //     "card_description" => "This is a short description of the item.",
            //     "card_save_button_route" => "employee-overtime.index",
            // ],
            [
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime Lines",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-overtime-lines.index",
            ],
            // [
            //     "endpoint_name_diginet" => "business-trip",
            //     "card_name" => "Employee Overtime",
            //     "card_description" => "This is a short description of the item.",
            //     "card_save_button_route" => "business-trip.index",
            // ],
            [
                "endpoint_name_diginet" => "business-trip",
                "card_name" => "Business Trip Lines",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "business-trip-lines.index",
            ],
        ];
    }

    public function index(Request $request)
    {
        $middleRoute = "/diginet/transfer-data-diginet";
        $dataRender = $this->getDataRender();
        return view('diginet.diginet-data', [
            "middleRoute" => $middleRoute,
            "dataRender" => $dataRender,
        ]);
    }
}

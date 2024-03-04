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

    public function getDataRender()
    {
        return [
            "employee-hours" => [
                "name" => "employee-hours",
                "field_index_name" => "",
                "endpoint_name_diginet" => "employee-hours",
                "card_name" => "Employee Hours",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-hours.index",
                "model_name" => "",
            ],
            // "employee-leave-sheets" => [
            //     "endpoint_name_diginet" => "employee-leave",
            //     "card_name" => "Employee Leave",
            //     "card_description" => "This is a short description of the item.",
            //     "card_save_button_route" => "employee-leave.index",
            //     "children_table" => "diginet_employee_leave_lines",
            //     "data_index" => 1
            // ],
            "employee-leave-lines" => [
                "name" => "employee-leave-lines",
                "field_index_name" => "",
                "endpoint_name_diginet" => "employee-leave",
                "card_name" => "Employee Leave Lines",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-leave-lines.index",
                "model_name" => "",
                "data_index" => 1
            ],

            "employee-overtime-sheets" => [
                "name" => "employee-overtime-sheets",
                "field_index_name" => "ot_date",
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime Sheet",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-overtime-sheets.index",
                "model_name" => "Diginet_employee_overtime_sheet",
                "children_table" => "diginet_employee_overtime_lines",
                "data_index" => 0
            ],
            "employee-overtime-lines" => [
                "name" => "employee-overtime-lines",
                "field_index_name" => "ot_date",
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime Lines",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "employee-overtime-lines.index",
                "model_name" => "Diginet_employee_overtime_line",
                "data_index" => 1
            ],
            // [
            //     "endpoint_name_diginet" => "business-trip",
            //     "card_name" => "Employee Overtime",
            //     "card_description" => "This is a short description of the item.",
            //     "card_save_button_route" => "business-trip.index",
            // ],
            "business-trip-sheets" => [
                "name" => "business-trip-sheets",
                "field_index_name" => "",
                "endpoint_name_diginet" => "business-trip",
                "card_name" => "Business Trip Lines",
                "card_description" => "This is a short description of the item.",
                "card_save_button_route" => "business-trip-lines.index",
                "model_name" => "",
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

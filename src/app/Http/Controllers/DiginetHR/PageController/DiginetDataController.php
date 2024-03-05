<?php

namespace App\Http\Controllers\DiginetHR\PageController;

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
            "all-tables" => [
                "name" => "all-tables",
                "field_index_name" => "",
                "endpoint_name_diginet" => "all-tables",
                "card_name" => "Update the Entire Database",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "all-tables.index",
                "card_btn2_route" => "",
                "card_btn3_route" => "",
                "model_name" => "",
                "data_index" => 0,
            ],
            "employee-hours" => [
                "name" => "employee-hours",
                "field_index_name" => "date",
                "endpoint_name_diginet" => "employee-hours",
                "card_name" => "Employee Hours",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-hours.index",
                "card_btn2_route" => "report-diginet_employee_hour_010",
                "card_btn3_route" => "report-diginet_employee_hour_010_ep.exportCSV",
                "model_name" => "Diginet_employee_hour",
                "data_index" => 0,
            ],
            "employee-leave-sheets" => [
                "name" => "employee-leave-sheets",
                "field_index_name" => "",
                "endpoint_name_diginet" => "employee-leave",
                "card_name" => "Employee Leave",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-leave-sheets.index",
                "card_btn2_route" => "report-diginet_employee_leave_line_010",
                "card_btn3_route" => "report-diginet_employee_leave_sheet_010_ep.exportCSV",
                "model_name" => "Diginet_employee_leave_sheet",
                "data_index" => 0
            ],
            "employee-leave-lines" => [

                "name" => "employee-leave-lines",
                "field_index_name" => "la_date",
                "endpoint_name_diginet" => "employee-leave",
                "card_name" => "Employee Leave Lines",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-leave-lines.index",
                "card_btn2_route" => "",
                "card_btn3_route" => "report-diginet_employee_leave_line_010_ep.exportCSV",
                "model_name" => "Diginet_employee_leave_line",
                "data_index" => 1
            ],
            "employee-overtime-sheets" => [
                "name" => "employee-overtime-sheets",
                "field_index_name" => "ot_date",
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-overtime-sheets.index",
                "card_btn2_route" => "report-diginet_employee_overtime_line_010",
                "card_btn3_route" => "report-diginet_employee_overtime_line_010_ep.exportCSV",
                "model_name" => "Diginet_employee_overtime_sheet",
                "data_index" => 0
            ],
            "employee-overtime-lines" => [
                "name" => "employee-overtime-lines",
                "field_index_name" => "ot_date",
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime Lines",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-overtime-lines.index",
                "card_btn2_route" => "",
                "card_btn3_route" => "",
                "model_name" => "Diginet_employee_overtime_line",
                "data_index" => 1
            ],
            "business-trip-sheets" => [
                "name" => "business-trip-sheets",
                "field_index_name" => "",
                "endpoint_name_diginet" => "business-trip",
                "card_name" => "Business Trip",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "business-trip-sheets.index",
                "card_btn2_route" => "report-diginet_business_trip_line_010",
                "card_btn3_route" => "report-diginet_business_trip_line_010_ep.exportCSV",
                "model_name" => "Diginet_business_trip_sheet",
                "data_index" => 0
            ],
            "business-trip-lines" => [
                "name" => "business-trip-lines",
                "field_index_name" => "tb_date",
                "endpoint_name_diginet" => "business-trip",
                "card_name" => "Business Trip Line",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "business-trip-lines.index",
                "card_btn2_route" => "",
                "card_btn3_route" => "",
                "model_name" => "Diginet_business_trip_line",
                "data_index" => 1
            ],
        ];
    }

    public function index(Request $request)
    {
        $middleRoute = "/diginet/transfer-data-diginet";
        $dataRender = $this->getDataRender();
        return view('diginet.diginet-data-many-cards', [
            "middleRoute" => $middleRoute,
            "dataRender" => $dataRender,
        ]);
    }
}

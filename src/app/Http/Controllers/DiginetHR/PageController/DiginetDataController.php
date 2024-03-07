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

            "employee-hours" => [
                "name" => "employee-hours",
                "field_index_name" => "date",
                "endpoint_name_diginet" => "employee-hours",
                "card_name" => "Employee Hours",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-hours.index",
                "card_btn2_route" => [
                    "employee-hours" => "report-diginet_employee_hour_010",
                ],
                "card_btn3_route" => [
                    "employee-hours" => "report-diginet_employee_hour_010_ep.exportCSV"
                ],
                "card_btn4_route" => [
                    "employee-hours" => "delete-database.delete",
                ],
                "model_name" => "Diginet_employee_hour",
            ],
            "employee-leave-sheets" => [
                "name" => "employee-leave-sheets",
                "field_index_name" => "la_date",
                "endpoint_name_diginet" => "employee-leave",
                "card_name" => "Employee Leave",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-leave-sheets.index",
                "card_btn2_route" => [
                    "employee-leave-sheets" => "report-diginet_employee_leave_sheet_010",
                    "employee-leave-lines" => "report-diginet_employee_leave_line_010",
                ],
                "card_btn3_route" => [
                    "employee-leave-sheets" => "report-diginet_employee_leave_sheet_010_ep.exportCSV",
                    "employee-leave-lines" => "report-diginet_employee_leave_line_010_ep.exportCSV"
                ],
                "card_btn4_route" => [
                    "employee-leave-sheets" => "delete-database.delete",
                    "employee-leave-lines" => "delete-database.delete"
                ],
                "model_name" => "Diginet_employee_leave_sheet",
            ],
            "all-tables" => [
                "name" => "all-tables",
                "field_index_name" => null,
                "endpoint_name_diginet" => "all-tables",
                "card_name" => "Update the Entire Database",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "all-tables.index",
                "card_btn2_route" => null,
                "card_btn3_route" => null,
                "card_btn4_route" => [
                    "all-tables" => "delete-all-tables.delete",
                ],
                "model_name" => "",
            ],
            "employee-leave-lines" => [

                "name" => "employee-leave-lines",
                "field_index_name" => "la_date",
                "endpoint_name_diginet" => "employee-leave",
                "card_name" => "Employee Leave Lines",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-leave-lines.index",
                "card_btn2_route" => null,
                "card_btn3_route" => null,
                "model_name" => "Diginet_employee_leave_line",
            ],
            "employee-overtime-sheets" => [
                "name" => "employee-overtime-sheets",
                "field_index_name" => "ot_date",
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-overtime-sheets.index",
                "card_btn2_route" => [
                    "employee-overtime-sheets" => "report-diginet_employee_overtime_sheet_010",
                    "employee-overtime-lines" => "report-diginet_employee_overtime_line_010",
                ],
                "card_btn3_route" => [
                    "employee-overtime-sheets" => "report-diginet_employee_overtime_sheet_010_ep.exportCSV",
                    "employee-overtime-lines" => "report-diginet_employee_overtime_line_010_ep.exportCSV"
                ],
                "card_btn4_route" => [
                    "employee-overtime-sheets" => "delete-database.delete",
                    "employee-overtime-lines" => "delete-database.delete"
                ],
                "model_name" => "Diginet_employee_overtime_sheet",
            ],
            "employee-overtime-lines" => [
                "name" => "employee-overtime-lines",
                "field_index_name" => "ot_date",
                "endpoint_name_diginet" => "employee-overtime",
                "card_name" => "Employee Overtime Lines",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "employee-overtime-lines.index",
                "card_btn2_route" => null,
                "card_btn3_route" => null,
                "model_name" => "Diginet_employee_overtime_line",
            ],
            "business-trip-sheets" => [
                "name" => "business-trip-sheets",
                "field_index_name" => "tb_date",
                "endpoint_name_diginet" => "business-trip",
                "card_name" => "Business Trip",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "business-trip-sheets.index",
                "card_btn2_route" => [
                    "business-trip-sheets" => "report-diginet_business_trip_sheet_010",
                    "business-trip-lines" => "report-diginet_business_trip_line_010"
                ],
                "card_btn3_route" => [
                    "business-trip-sheets" => "report-diginet_business_trip_sheet_010_ep.exportCSV",
                    "business-trip-lines" => "report-diginet_business_trip_line_010_ep.exportCSV"
                ],
                "card_btn4_route" => [
                    "business-trip-sheets" => "delete-database.delete",
                    "business-trip-lines" => "delete-database.delete"
                ],
                "model_name" => "Diginet_business_trip_sheet",
            ],
            "business-trip-lines" => [
                "name" => "business-trip-lines",
                "field_index_name" => "tb_date",
                "endpoint_name_diginet" => "business-trip",
                "card_name" => "Business Trip Line",
                "card_description" => "This is a short description of the item.",
                "card_btn1_route" => "business-trip-lines.index",
                "card_btn2_route" => null,
                "card_btn3_route" => null,
                "model_name" => "Diginet_business_trip_line",
            ],
        ];
    }

    public function index(Request $request)
    {
        $middleRoute = $request->getPathInfo();
        $dataRender = $this->getDataRender();

        return view('diginet.diginet-data-many-cards', [
            "middleRoute" => $middleRoute,
            "dataRender" => $dataRender,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $widget = [
                    "title_a" => "Inspection Checklists",
                    "title_b" => "by Status (Inspection Checksheet (ISC))",
                    "meta" =>[
                                "labels" => "['Scope 1', 'Scope 2', 'Scope 3']",
                                "numbers" => "[200, 300,400]",
                                "max" => 5,
                                "count" => 2],
            "metric" =>[(object)[
                                "metric_id"=> 1,
                                "metric_name"=> "in_progress",
                                "metric_count"=> 4
                            ],
                            (object)[
                                "metric_id"=> 2,
                                "metric_name"=> "approved",
                                "metric_count"=> 1
                            ]],
            "chartType" => "doughnut",
            "hidden" => null,
            "name" => "chklst_01",
            "chart_type" => "doughnut",
            "fn" => "SqlForeignKeyWidget01",
            "section_title" => "Inspection Checklists",
            "widget_title" => "by Status (Inspection Checksheet (ISC))",
            "params" =>[
              "table_a" => "prod_orders",
              "key_a1" => "sub_project_id",
              "key_a2" => "id",
              "table_b" => "qaqc_insp_chklsts",
              "key_b1" => "prod_order_id",
              "att_metric_name" => "status",
              "qaqc_insp_tmpl_id" => "1",
              "table_widget" => null,
              "check_id" => null,
            ]
        ];
          
        return view("welcome-due", [
        ]);
    }
}

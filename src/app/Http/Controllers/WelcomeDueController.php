<?php

namespace App\Http\Controllers;

use App\Utils\Support\AttachmentName;
use Illuminate\Http\Request;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {


        AttachmentName::testCasesUploadMedia();


        $widget =  [
            "title_a" => "Production Orders",
            "title_b" => "by Status",
            "meta" =>  [
                "labels" => "['finished', 'new', 'in_progress']",
                "numbers" => "[10, 20, 30]",
                "max" => 50,
                "count" => 3,
            ],
            "metric" =>  (object)[
                [
                    "metric_id" =>  1,
                    "metric_name" =>  "finished",
                    // "metric_count" =>  10,
                ],
                [
                    "metric_id" =>  2,
                    "metric_name" =>  "new",
                    // "metric_count" =>  20,
                ],
                [
                    "metric_id" =>  3,
                    "metric_name" =>  "in_progress",
                    // "metric_count" =>  30,
                ]
            ],
            "chartType" => "bar",
            "hidden" => null,
            "name" => "prod_order_status",
            "report_name" => null,
            "chart_type" => "bar",
            "fn" => "SqlForeignKeyWidget01",
            "section_title" => "Production Orders",
            "widget_title" => "by Status",
            "params" =>  [
                "table_a" => "prod_orders",
                "key_a1" => "sub_project_id",
                "att_metric_name" => "status",
                "table_widget" => null,
                "check_id" => null,
            ]
        ];


        return view("welcome-due", [
            'widget' => $widget
        ]);
    }
}

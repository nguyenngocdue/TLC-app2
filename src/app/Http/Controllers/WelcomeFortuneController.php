<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WelcomeFortuneController extends Controller
{
    function __construct() {}

    function getType()
    {
        return "dashboard";
    }

    function getActions()
    {
        return [
            //Dashboard
            ["dataIndex" => "show_all_ics_by_sub_project"],
            ["dataIndex" => "show_some_routing_by_prod_routing"],

            ["dataIndex" => "show_some_ics_by_sign_off_box"],
            ["dataIndex" => "show_some_ics_by_shipping_agent_box"],
            ["dataIndex" => "show_some_ics_by_council_member_box"],
            //Checklist
            ["dataIndex" => "be_able_to_checkpoint_change"],
            ["dataIndex" => "be_able_to_checkpoint_upload_photo"],
            ["dataIndex" => "be_able_to_checkpoint_comment"],
        ];
    }

    public function index(Request $request)
    {
        $thirdPartyDisciplineIds = User::get3rdPartyDisciplineIds();

        $terms = User_discipline::query()
            ->whereIn('id', $thirdPartyDisciplineIds)
            ->get();

        dump($terms->pluck('name')->toArray());


        // return view("welcome-fortune", [
        //     'columns' => $columns,
        //     'dataSource' => $tables,
        // ]);
    }
}

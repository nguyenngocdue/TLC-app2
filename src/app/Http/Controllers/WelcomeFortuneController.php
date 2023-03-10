<?php

namespace App\Http\Controllers;

use App\Models\User_team_ot;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $list = User_team_ot::get()->toArray();
        // dump($list);
        $dataSource = [];
        foreach ($list as $team) {
            $dataSource[$team['id']] = $team['name'];
        }
        dump($dataSource);
        return view("welcome-fortune", [
            'teamList' => $list,
            'dataSource' => $dataSource,
            'itemSelected' => ['name' => 1],
        ]);
    }
}

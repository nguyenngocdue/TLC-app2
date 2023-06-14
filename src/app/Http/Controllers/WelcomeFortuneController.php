<?php

namespace App\Http\Controllers;

use App\Utils\Constant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        // $db = (new EntityIdClickCount)('project');
        // dump($db);
        // $db = (new EntityNameClickCount)(560);
        // dump($db);

        $out = Carbon::createFromFormat(Constant::FORMAT_DATETIME_MYSQL, "2022-01-31 00:00:00");

        $out = $out->setTimezone(7)->format(Constant::FORMAT_DATETIME_ASIAN);
        dump($out);

        $dataSource = [];

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
        ]);
    }
}

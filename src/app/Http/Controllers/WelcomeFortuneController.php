<?php

namespace App\Http\Controllers;

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

        $dataSource = [];

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
        ]);
    }
}

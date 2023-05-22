<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\AccessLogger;
use App\Utils\AccessLogger\EntityClickCount;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $db = (new EntityClickCount)('project');
        dump($db);
        $dataSource = [];

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
        ]);
    }
}

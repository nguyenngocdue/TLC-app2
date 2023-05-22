<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\AccessLogger;
use App\Utils\AccessLogger\EntityIdClickCount;
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
        $db = (new EntityIdClickCount)('project');
        dump($db);
        $dataSource = [];

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
        ]);
    }
}

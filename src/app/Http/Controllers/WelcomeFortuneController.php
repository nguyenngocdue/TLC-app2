<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeFortuneController extends Controller
{
    function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        $connection = env('SQLSERVER_CONNECTION', 'sqlsrv');
        // dump($connection);

        $tables = DB::connection($connection)->select('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \'BASE TABLE\' AND TABLE_CATALOG= ?', [env('SQLSERVER_DATABASE')]);
        dump($tables);
        return view("welcome-fortune", []);
    }
}

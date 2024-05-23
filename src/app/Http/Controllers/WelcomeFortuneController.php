<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WelcomeFortuneController extends Controller
{
    function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        // $connection = env('SQLSERVER_CONNECTION', 'sqlsrv');
        // // dump($connection);

        // $tables = DB::connection($connection)->select('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \'BASE TABLE\' AND TABLE_CATALOG= ?', [env('SQLSERVER_DATABASE')]);
        // dump($tables);

        // BTH, STW, NGH, GHT
        // HLC MIR, HLC_OFFSITE_BCCompliance, HLC_OFFSITE_MFGCompliance
        //HLC_OFFSITE_N17, HLC_OFFSITE_N18, HLC_SHIPPING,
        // $files = Storage::disk('conqa_backup')->files("/HLC_OFFSITE_Prototype/file");
        // dump(sizeof($files));

        // $files1 = Storage::disk('conqa_attachment')->files("/HLC_OFFSITE_Prototype/file");
        // dump(sizeof($files1));

        // $diff = array_diff($files1, $files);
        // dump($diff);

        return view("welcome-fortune", []);
    }
}

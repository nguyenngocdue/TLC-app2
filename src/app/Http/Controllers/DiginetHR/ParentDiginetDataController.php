<?php

namespace App\Http\Controllers\DiginetHR;

use App\Http\Controllers\Controller;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class ParentDiginetDataController extends Controller
{
    protected $endpointNameDiginet;
    protected $topTitle = 'Update Diginet Data';
    protected $title;

    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $token = CurrentUser::getTokenForApi();
        return view("diginet.diginet-transfer-data", [
            'token' => $token,
            'endpointNameDiginet' => $this->endpointNameDiginet,
            'topTitle' => $this->topTitle,
            'title' => $this->title
        ]);
    }
    public function delete(Request $request)
    {
        $entity = $request->input()['entity'];
        $table = 'diginet_' . str_replace('-', '_', Str::plural($entity));
        $data = DB::table($table)->delete();
        if (!$data) return "Data in [$table] table cleaned successfully.";
        return "Failed to clean data in [$table] table.";
    }
}

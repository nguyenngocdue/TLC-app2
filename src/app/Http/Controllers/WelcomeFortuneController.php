<?php

namespace App\Http\Controllers;

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

        dump(SuperProps::getFor('qaqc_wir'));
        dump(SuperWorkflows::getFor('qaqc_wir'));

        return view("welcome-fortune", [
            // ''
        ]);
    }
}

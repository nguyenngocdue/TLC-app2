<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\Statuses;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_run;
use App\Models\Qaqc_insp_sheet;
use App\Models\Qaqc_insp_tmpl;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    }
}

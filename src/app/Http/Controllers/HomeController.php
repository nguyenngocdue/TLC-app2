<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\Statuses;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_sheet;
use App\Models\Qaqc_insp_tmpl;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        // $user = User::where('email', 'admin')->first();
        // GetSetCookie::setCookieForever('time_zone', $user->time_zone);
        // $token = $user->createToken('tlc_token')->plainTextToken;
        $array = array(
            'abc' => [
                1, 2, 3
            ],
            'friends' => [
                'Chris',
                'Jim',
                'Lynn',
                'Jeff',
                'Joanna',
            ],
            'websites' => [
                'Search' => 'Google',
                'Social' => 'Facebook',
                'New' => 'NY Times'
            ],


        );
        dd(Qaqc_insp_sheet::find(1)->getChklstLines->where('qaqc_insp_chklst_id', 1)->groupBy('qaqc_insp_group_id'));
        // dump(array_merge($array['websites'], $array['friends']));
        dd(array_shift($array));
        dump(array_pop($array));
        dd($array);
        dd(array_values(array_map(fn ($value) => $value['title'], Statuses::getFor('sub_project'))));
        // dump(User::where('email', 'admin')->first());
        // dump(CurrentUser::getRoles());
        // dd(CurrentUser::getRoles(User::where('email', 'admin')->first()));
        // return response()->json([
        //     'user' => $user,
        // ]);
    }
}

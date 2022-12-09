<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\Statuses;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
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
        dd(array_values(Statuses::getFor('sub_projects')));
        dd(array_values(array_map(fn ($value) => $value['title'], Statuses::getFor('sub_project'))));
        // dump(User::where('email', 'admin')->first());
        // dump(CurrentUser::getRoles());
        // dd(CurrentUser::getRoles(User::where('email', 'admin')->first()));
        // return response()->json([
        //     'user' => $user,
        // ]);
    }
}

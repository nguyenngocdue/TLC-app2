<?php

namespace App\Http\Controllers\Auth;

use App\Utils\Support\CurrentUser;
use App\Utils\System\GetSetCookie;
use Illuminate\Http\Request;

class PostLoggedInActions
{
    public static function setTokenWhenLoggedForCookie()
    {
        $user = CurrentUser::get();
        // $timezone = $user->time_zone;
        // GetSetCookie::setCookieForever('time_zone', $timezone);
        $token = $user->createToken('tlc_token')->plainTextToken;
        GetSetCookie::setCookieForever('tlc_token', $token);
    }
    public static function setTimeZoneWhenLogged(Request $request)
    {
        if ($value = $request->input('time_zone')) {
            $user = CurrentUser::get();
            $user->time_zone = $value;
            $user->update();
        }
    }
}

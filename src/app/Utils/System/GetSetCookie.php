<?php

namespace App\Utils\System;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class GetSetCookie
{
    public static function setCookie($name, $value, $time)
    {
        $cookie = Cookie::queue(Cookie::make($name, $value, $time));
        return $cookie;
    }
    public static function setCookieForever($name, $value)
    {
        $cookie = Cookie::queue(Cookie::forever($name, $value));
        return $cookie;
    }
    public static function forgetCookie($name)
    {
        return Cookie::queue(Cookie::forget($name));
    }

    public static function getCookie($name = null)
    {
        return Cookie::get($name);
    }
    public static function hasCookie($name)
    {
        return Cookie::has($name);
    }
}

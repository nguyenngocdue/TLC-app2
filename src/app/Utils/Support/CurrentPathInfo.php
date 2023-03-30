<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;

class CurrentPathInfo
{
    public static function getTypeReport($request)
    {
        $pathInfo = $request->getPathInfo();
        $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[0]);
        return strtolower(Str::plural($pathInfo[0]));
    }
    public static function getEntityReport($request)
    {
        $pathInfo = $request->getPathInfo();
        $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[1]);
        return strtolower(Str::plural($pathInfo[1]));
    }
    public static function getViewName($request)
    {
        $pathInfo = $request->getPathInfo();
        $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[1]);
        return $pathInfo[0];
    }
}
